import axios from 'axios'

const fetchAuctions = async (path, type = 'active') => {
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
            
            switch (type) {
                case 'active':
                    charityAuctions = auctions.find(auction => {
                        return auction.type === 'charity'
                    });
        
                    commercialAuctions = auctions.find(auction => {
                        return auction.type === 'commercial'
                    });
                    break;
            
                case 'dismissed':
                    charityAuctions = auctions.filter(auction => {
                        return auction.type === 'charity'
                    });
        
                    commercialAuctions = auctions.filter(auction => {
                        return auction.type === 'commercial'
                    });
                    break;

                case 'latest':
                    charityAuctions = auctions.filter(auction => {
                        let now = new Date();
                        return (
                            auction.type === 'charity'
                            && new Date(auction.started_at) <= now
                            && new Date(auction.started_at) >= new Date(now.getDate() - 3)
                        )
                    });
        
                    commercialAuctions = auctions.filter(auction => {
                        let now = new Date();
                        return (
                            auction.type === 'commercial'
                            && new Date(auction.started_at) <= now
                            && new Date(auction.started_at) >= new Date(now.getDate() - 3)
                        )
                    });
                    break;
            }

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