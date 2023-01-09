// Add new participant to the active auction
import axios from 'axios'

const insertAuctionParticipant = async (token, userId, auctionId, price, response, isPaid) => {
    return new Promise((resolve, reject) => {
        const config = { 
            headers: { 
            'Authorization': 'Bearer '+token,
            'Content-Type': 'multipart/form-data' 
            }
        };
        const participantData = new FormData();
        participantData.append('auction_id', auctionId);
        participantData.append('user_id', userId);
        participantData.append('amount', price);
    
        axios
            .post(
            'auth/auction/addParticipant',
            participantData,
            config
            )
            .then(res => {
                switch (res.status) {
                    case 200:
                        response.status = 'Success'
                        response.message = `Successful participation in the auction!`
                    break
    
                    case 500:
                        response.status = 'Error'
                        response.message = 'Server is not responding. Error code: 500'
                    break
                
                    default:
                        response.status = 'Hmm...'
                        response.message = 'Something went wrong...'
                    break
                }
                isPaid = true;
    
                resolve({
                    response,
                    isPaid
                });
            })
            .catch(err => {
                console.log(err);
                reject({});            
            })
    });
}

export default insertAuctionParticipant;