<?php

namespace App\Http\Controllers;

use App\AuctionObject;
use App\AuctionObjectType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuctionObjectController extends Controller
{
    public function index_types() {
        $objects = AuctionObjectType::get();

        return response()->json([
            'status' => 'success',
            'obj_types' => $objects->toArray()
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($objectName, $previewImage)
    {
        $newLabel = md5(time()+rand()).'.'.$previewImage->getClientOriginalExtension();
        $previewImage->storeAs(
            'public/uploads/auctions/auction_objects', $newLabel
        );
        $imagePath = Storage::disk('public')->url('uploads/auctions/auction_objects/'.$newLabel);

        $auctionObject = new AuctionObject();
        $auctionObject->object_type_id = 2;
        $auctionObject->name = $objectName;
        $auctionObject->preview_image = $imagePath;
        $auctionObject->save();
        return $auctionObject;
    }

    public function storeType(Request $request)
    {
        $type = new AuctionObjectType;
        $type->label = $request->label;
        $type->save();
        return response()->json(['status' => 'success'], 200);
    }

    public function deleteType(Request $request) {
        $typeToDelete = AuctionObjectType::find($request->type);
        $dependantAuctionObjects = AuctionObject::where('object_type_id', '=', $typeToDelete->id)->get();
        if (!$dependantAuctionObjects->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Some auction objects already depend on this object type!'
            ], 200);
        }
        $typeToDelete->delete();
        return;
    }
}
