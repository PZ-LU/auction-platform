// Add new participant to the active auction
import axios from 'axios'

const insertAuctionParticipant = async (token, userId, auctionId, price, response, isPaid, type) => {
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
            `auth/auction/addParticipant${type.charAt(0).toUpperCase() + type.slice(1)}`,
            participantData,
            config
            )
            .then(res => {
                switch (res.status) {
                    case 200:
                        response.status = 'Success'
                        response.message = {
                            title: 'Successful participation in the auction!',
                            body: 'You request will be processed.'
                        }
                    break
    
                    case 500:
                        response.status = 'Error'
                        response.message = {
                            title: 'Server is not responding. Error code: 500',
                        }
                    break
                
                    default:
                        response.status = 'Hmm...'
                        response.message = { title: 'Server is not responding. Error code: 500', body: null }
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