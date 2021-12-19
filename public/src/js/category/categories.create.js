var app_events_create = new Vue({
    el: '#app-create-categories',
    delimiters: ['${', '}'],
    data: {
        language: 'es',
        slug: '',
        name: '',
    },
    methods: {
        validateData: function () {
            if (this.slug == "") {
                Toast.fire({
                    icon: 'warning',
                    title: 'El Slug es un identificador del Evento por lo tanto es requerido.'
                })
                return false;
            }

            if (this.name == "") {
                Toast.fire({
                    icon: 'warning',
                    title: 'El Nombre del Evento es requerido.'
                })
                return false;
            }
            return true;
        },

        saveData: function (event) {
            if (!this.validateData()) return;
            let formData = {
                slug: this.slug,
                name: this.name,
                language: this.language,
            };

            axios.post(event.target.attributes.action.value, formData)
                .then(rs => {
                    Toast.fire({
                        icon: 'success',
                        title: 'Se ha creado con éxito.'
                    }).then(rs=>window.location.reload());
                })
                .catch(err => {
                    let errors = err.response.data.errors;
                    if (errors != undefined) {
                        console.log(errors);
                        Object.keys(errors).forEach(keyError => {
                            Toast.fire({
                                icon: 'error',
                                title: errors[keyError]
                            })
                        });
                    }
                });
        }


    }
})
