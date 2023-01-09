// Often used auction fetching based on type

import axios from 'axios'

const fetchAuctions = async (type = 'active') => {
    let path = 'auctions?status=';
    switch (type) {
        case 'active':
            path += "active";
            break;
    
        case 'dismissed':
            path += 'dismissed';
            break;

        case 'latest':
            path += 'all&limit=5';
            break;
    }

    return new Promise((resolve, reject) => {
        axios
        .get(path)
        .then(res => {          
            const {data:{data}} = res;
            let auctions;
            if (!data) {
                return
            } else if (data.length) {
                auctions = data
            } else {
                auctions = [data]
            }

            let charityAuctions;
            let commercialAuctions;

            // Split into 2 lists
            charityAuctions = auctions.filter(auction => {
                return auction.type === 'charity'
            });

            commercialAuctions = auctions.filter(auction => {
                return auction.type === 'commercial'
            });

            resolve({
                charityAuctions,
                commercialAuctions
            });
        })
        .catch(err => {
            console.log(`Error fetching active auctions: ${err}`)  
            reject({});        
        });
    });
}

export default fetchAuctions;