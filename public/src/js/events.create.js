var app_events_create = new Vue({
    el: '#app-create-events',
    delimiters: ['${', '}'],
    data: {
        categories: [],
        languageNow: 'es',
        // slug: 'carta',
        // name: 'carta',
        // capacity: '53',
        // date: '2022-02-18',
        // category_id: 1,
        slug: '',
        name: '',
        capacity: '0',
        date: '',
        category_id: 0,
    },
    methods: {
        setCategories: function (categories) {
            this.categories = categories;
        },

        getCategories: function () {
            let filterCategories = [];
            filterCategories.push({
                id: 0,
                description: "Seleccione una opción",
                language: null
            });

            this.categories.forEach(category => {
                if (category.descriptions.length == 0) return;

                let idiomSelectedAlternative = {
                    id: category.id,
                    description: category.descriptions[0].name,
                    language: category.descriptions[0].language
                };

                let idiomSelected = null;

                category.descriptions.forEach(description => {

                    if (description.language == 'es') {
                        idiomSelectedAlternative = {
                            id: category.id,
                            description: description.name,
                            language: description.language
                        };
                    }
                    if (description.language == this.languageNow) {
                        idiomSelected = {
                            id: category.id,
                            description: description.name,
                            language: description.language
                        };
                    }

                });
                if (idiomSelected == null) {
                    idiomSelected = idiomSelectedAlternative;
                }
                filterCategories.push(idiomSelected);
            });
            return filterCategories;
        },

        validateData: function () {
            if (this.category_id == 0) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Debe seleccionar una categoría.'
                })
                return false;
            }

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

            if (!Number.isInteger(parseInt(this.capacity))) {
                Toast.fire({
                    icon: 'warning',
                    title: 'La capacidad del Evento debe ser un valor númerico.'
                })
                return false;
            }

            if (this.capacity == 0) {
                Toast.fire({
                    icon: 'warning',
                    title: 'La capacidad del Evento debe ser mayor a 0.'
                })
                return false;
            }


            if (!this.isTheDateAfterNow(this.date)) {
                alert("La fecha del evento debe ser posterior a la fecha actual");
                return false;
            }

            return true;
        },

        isTheDateAfterNow: function (date) {
            var dateInput = new Date(date); 
            var dateNow = new Date();
            console.log(dateInput, dateNow);
            if (dateInput > dateNow) {
                return true;
            }
            return false;
        },

        saveData: function (event) {
            if (!this.validateData()) return;
            let formData = {
                slug: this.slug,
                name: this.name,
                capacity: parseInt(this.capacity),
                date: this.date,
                language: this.languageNow,
                category_id: this.category_id,
            };

            axios.post(event.target.attributes.action.value, formData)
                .then(rs => {
                    Toast.fire({
                        icon: 'success',
                        title: 'Se ha creado con éxito.'
                    }).then(rs=>window.location.reload());
                })
                .catch(err => {
                    console.log(err);
                    // Toast.fire({
                    //     icon: 'error',
                    //     title: 'Ha ocurrido un error.'
                    // }).then(rs=>cons);
                });
        }


    },
    watch: {
        capacity: function (newQuestion, oldQuestion) {
            if (newQuestion < 0) {
                this.capacity = 0;
                Toast.fire({
                    icon: 'warning',
                    title: 'La cantidad no puede ser menor a 0.'
                })
            }
        }

    }
})
