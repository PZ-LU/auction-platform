// Fetch user's favorite offers
import axios from 'axios'

const fetchFavoriteOffers = async (userId) => {
    return new Promise((resolve, reject) => {
        axios
            .get(`/offers/favorites/${userId}`)
            .then(res => {
                resolve(res.data);
            })
            .catch(err => {
                console.log(err);      
                reject([]); 
            })
    })
  }

export default fetchFavoriteOffers;