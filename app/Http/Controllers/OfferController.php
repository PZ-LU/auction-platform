<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Offer;
use App\Http\Resources\Offers\Offer as OfferResources;
use App\Traits\OffersTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class OfferController extends Controller
{
    use OffersTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $offersQuery = Offer::query();

        // offer sorting by creation date
        if (!is_null($request->date)) {
            switch ($request->date) {
                case 'ascending':
                    $offersQuery->orderBy('created_at','asc');
                    break;
                
                case 'descending':
                    $offersQuery->orderBy('created_at','desc');
                    break;
            }
        } else {
            $offersQuery->orderBy('created_at','desc');
        }

        // conditions from route query parameters
        if(!is_null($request->title)) {
            $offersQuery->where('title', 'like', '%'.$request->title.'%');
        }

        if(!is_null($request->description)) {
            $offersQuery->where('body', 'like', '%'.$request->description.'%');
        }

        $offers = Offer::where('status', Offer\Status::ACTIVE);
        if(!is_null($request->tags)) {
            $offerIds = DB::table('offers_tags')
                ->where('tag', 'like', '%'.$request->tags.'%')
                ->groupBy('offer_id')
                ->pluck('offer_id')
                ->toArray();

            $offersQuery->whereIn('id', $offerIds);
        }

        $filterOfferIds = [];
        if(!is_null($request->category)) {
            $offerIds = DB::table('offers_tags')
                ->whereIn('category', (array) $request->category)
                ->groupBy('offer_id')
                ->pluck('offer_id');
            $offersQuery->whereIn('id', $offerIds);
        }
        $offersQuery->where('status', Offer\Status::ACTIVE);

        $offers = $offersQuery;

        $offers = $offers->paginate(5);
        foreach ($offers as $offer) {
            $offer->author_info = $this->getOfferAuthor($offer->author_id);
        }

        return OfferResources::collection($offers)->additional([
            'meta' => [
                'version' => '1.0.0',
                'API_base_url' => url('/')
            ]
        ]);
    }

    public function getAll () {
        $offers = Offer::where('status', Offer\Status::ACTIVE)->get();
        foreach ($offers as $offer) {
            $offer->author_info = $this->getOfferAuthor($offer->author_id);
        }
        return OfferResources::collection($offers);
    }

    public function getServiceOffers () {
        $offers = Offer::all();
        foreach ($offers as $offer) {
            $offer->author_info = $this->getOfferAuthor($offer->author_id);
        }
        return OfferResources::collection($offers);
    }

    public function getFavoriteOffers (Request $request) {
        return DB::table('favorite_offers')->where('user_id', $request->user_id)->get();
    }

    public function changeFavorite (Request $request) {
        if ($request->action == 'set') {
            DB::table('favorite_offers')->insert(
                ['user_id' => $request->user_id, 'offer_id' => $request->offer_id]
            );
        }

        if ($request->action == 'delete') {
            DB::table('favorite_offers')
            ->where('user_id', $request->user_id)
            ->where('offer_id', $request->offer_id)
            ->delete();
        }
    }

    public function getUserOffers (Request $request) {
        $offers = Offer::where('author_id', '=', $request->user_id)->where('status', Offer\Status::ACTIVE)->get();
        if (sizeof($offers) < 1) {
            return [];
        }

        if (sizeof($offers) > 1) {
            foreach ($offers as $offer) {
                $offer->author_info = $this->getOfferAuthor($request->user_id);
            }
        } else {
            $offers[0]->author_info = $this->getOfferAuthor($request->user_id);
        }

        try {
            return OfferResources::collection($offers);
        } catch (\Throwable $th) {
            return new OfferResources($offers);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $offer = new Offer;
        $offer->author_id = $request->user_id;
        $offer->title = $request->title;
        $offer->body = $request->body;
        $offer->contact_phone = $request->contact_phone;
        $offer->save();

        $this->storeOfferTags($request->tags, $offer->id);

        $previewImage = self::storeImages($offer->id, $request->images, $request->preview_image_id);
        $offer->preview_image = $previewImage;
        $offer->save();

        return response()->json(['status' => 'success'], 200);
    }

    public function storeImages($offerId, $filesObj, $preview_image_id)
    {
        $previewImage = null;
        foreach ($filesObj as $fileId => $file) {
            $newLabel = null;
            $name_passed = false;

            // Compose new label for picture and check if it is unique
            do {
                $newLabel = md5(time()+rand()).'.'.$file->getClientOriginalExtension();

                if (!DB::table('offers_media')->where('file_name', $newLabel)) {
                    $name_passed = true;
                }
            } while ($name_passed = false);   

            $path = $file->storeAs(
                'public/uploads/offers_media', $newLabel
            );

            DB::table('offers_media')->insert(
                [
                    'offer_id' => $offerId,
                    'photo_path' => Storage::disk('public')->url('uploads/offers_media/'.$newLabel),
                    'file_name' => $newLabel
                ]
            );

            if ($fileId == $preview_image_id) {
                $previewImage = Storage::disk('public')->url('uploads/offers_media/'.$newLabel);
            }
        }
        return $previewImage ? $previewImage : Storage::disk('public')->url('uploads/offers_media/'.$newLabel);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $offer = Offer::find($id);
        $offer['tags'] = $this->showOfferTags($id);
        return new OfferResources($offer);
    }
    
    public function media($id)
    {
        $mediaCollection = collect([]);
        $rawMedia = DB::table('offers_media')->get()->where('offer_id', $id);
        foreach ($rawMedia as $data) {
            $mediaCollection = $mediaCollection->concat(['file_name' => Storage::disk('public')->url('uploads/offers_media/'.$data->file_name)]);
        }

        return response()->json([
            'status' => 'success',
            'photo_path' => $mediaCollection->toArray()
        ], 200);
    }

    public function softDelete(Request $request) {
        $offer = Offer::find($request->id);
        $offer->status = 'archived';
        $offer->save();
        return;
    }
}
