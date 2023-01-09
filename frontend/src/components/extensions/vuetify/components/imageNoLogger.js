import { VImg } from 'vuetify/lib'

// Add custom handler on image error
export default {
    name: 'v-img-nologger',
    extends: VImg,
    methods: {
        onError () {
            this.$emit('error')
        }
    }
};