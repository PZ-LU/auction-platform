/* Custom validation rules for Vuetify components */
const external_rules = {
    required: v => !!v || 'This field is required',
    requiredItem: v => ((v && v=='male') || (v && v=='female')) || 'This item is required',
    length: {
        name: v => ((!v) || (v && v.length < 24)) || 'Name must be less than 24 characters',
        surname: v => ((!v) || (v && v.length < 24)) || 'Surname must be less than 24 characters',
        username: v => ((v && v.length >= 5) && (v && v.length < 17))  || 'Username must be more than 5 and less than 16 characters',
        newUsername: v => (!v || ((v && v.length >= 5) && (v && v.length < 17)) ) || 'Username must be more than 5 and less than 16 characters',
        password: v => ((v && v.length >= 8) && (v && v.length < 17))  || 'Password must be more than 8 and less than 16 characters',
        newPassword: v => (!v || ((v && v.length >= 8) && (v && v.length < 17)) ) || 'Password must be more than 8 and less than 16 characters',
        commentBody: v => ((!v) || (v && v.length <= 600)) || 'Comment must be less than 600 characters',
    },
    types: {
        integersOnly: v => ((!v) || (/^\d+$/.test(v))) || 'Only integer numbers are allowed',
    },
    textOnly: v => (!v || /^[a-zA-Z_-]+$/.test(v)) || 'Field can contain letters only',
    email: v => /.+@.+\..+/.test(v) || 'Email must be valid',
    newEmail: v => (!v ||  /.+@.+\..+/.test(v) ) || 'Email must be valid'
};

export default external_rules;