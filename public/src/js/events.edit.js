var app_events_edit = new Vue({
    el: '#app-edit-events',
    delimiters: ['${', '}'],
    data: {
        //urls
        urlCreateDescription: '',
        urlUpdatePartially: '',
        urlDeleteDescription: '',

        //lists
        categories: [],
        descriptions: [],

        //information
        languageNow: 'es',
        slug: '',
        capacity: '0',
        date: '',
        category_id: 0,
        tempDescription: "",
        event_id: 0,
        newDescription: {
            name: "",
            language: ""
        }
    },
    methods: {
        setCategories: function (categories) {
            this.categories = categories;
        },

        setUrlCreateDescription: function (url) {
            this.urlCreateDescription = url;
            this.urlDeleteDescription = url;

        },

        setUrlUpdatePartially: function (url) {
            this.urlUpdatePartially = url;
        },

        setUrlDeleteDescription: function (url) {
            // this.urlDeleteDescription = url;
        },

        loadData: function (fields) {
            console.log(fields);
            this.slug = fields.slug;
            this.capacity = fields.capacity;
            this.date = fields.date;
            this.category_id = fields.category_id;
            this.event_id = fields.id;

            fields.descriptions.forEach(description => {
                this.addDescription(description);
            });
            this.newDescription.event_id = this.event_id;
        },

        addDescription: function (eventDescription) {
            this.descriptions.push({
                id: eventDescription.id,
                name: eventDescription.name,
                language: eventDescription.language,
                isEditing: false,
            });
        },

        activeEditForDescription: function (idDescription) {
            this.descriptions.forEach(description => {
                if (description.id == idDescription) {
                    description.isEditing = true
                    this.tempDescription = description.name;
                } else {
                    description.isEditing = false;
                }
            });
        },

        updateEvent: function () {
            let formData = {
                slug: this.slug,
                capacity: this.capacity,
                date: this.date,
                category_id: this.category_id,
            };
            if (!this.validateDataEvent()) return;

            axios.put(this.urlUpdatePartially, formData)
                .then(rs => {
                    let data = rs.data;
                    console.log(data);
                    if (data.state != true) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Ha ocurrido un error.'
                        }).then(rs => window.location.reload());
                        return;
                    }


                    this.slug = data.event.slug;
                    this.capacity = data.event.capacity;
                    this.date = data.event.date;
                    this.category_id = data.event.category_id;
                    console.log("sgvsdfg egarewgaer ge");
                    Toast.fire({
                        icon: 'success',
                        title: 'Se actualizó con éxito, el evento.'
                    });

                })
                .catch(err => {
                    console.log(err);
                    console.log(err.response);
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
        },

        createNewDescription: function () {
            if(this.newDescription.name == ""){
                Toast.fire({
                    icon: 'warning',
                    title: 'El nombre de la descripción es requerido.'
                })
                return false;
            }

            let formData = {
                ...this.newDescription
            };

            axios.post(this.urlCreateDescription, formData)
                .then(rs => {
                    let data = rs.data;
                    if (data.state) {
                        if (data.state != true) {
                            Toast.fire({
                                icon: 'error',
                                title: 'Ha ocurrido un error.'
                            }).then(rs => window.location.reload());
                            return;
                        }

                        if (rs.data.type_event == "CREATE") {
                            this.addDescription(rs.data.event_description);
                            Toast.fire({
                                icon: 'success',
                                title: 'Se ha creado la nueva descripción con éxito.'
                            })
                            return;
                        }

                        if (rs.data.type_event == "UPDATE") {
                            this.updateDescription(rs.data.event_description);
                            Toast.fire({
                                icon: 'success',
                                title: 'Se ha detectado el lenguaje ingresado, además se actualizó.'
                            });
                        }

                        if (rs.data.type_event == "RESTORE") {
                            this.addDescription(rs.data.event_description);
                            Toast.fire({
                                icon: 'success',
                                title: 'Se ha detectado el lenguaje ingresado, se ha restaurado y se actualizó correctamente.'
                            });
                        }
                        
                        this.newDescription.name = "";
                        this.newDescription.language = "";
                        
                    }

                })
                .catch(err => {
                    Toast.fire({
                        icon: 'error',
                        title: 'Ha ocurrido un error.'
                    })
                });
        },

        updateDescription: function (eventDescription) {
            this.descriptions.forEach(description => {
                if (description.language == eventDescription.language) {
                    description.name = eventDescription.name;
                }
            });
        },

        saveDescription: function (idDescription) {
            this.descriptions.forEach(description => {
                description.isEditing = false;
                if (description.id == idDescription) {
                    this.tempDescription = "";
                }

            });
        },


        deleteDescription: function (idDescription) {
            axios.delete(this.urlDeleteDescription + "/" + idDescription)
                .then(rs => {
                    let data = rs.data;
                    if (data.state) {
                        if (data.state != true) {
                            Toast.fire({
                                icon: 'error',
                                title: 'Ha ocurrido un error.'
                            }).then(rs => window.location.reload());
                            return;
                        }

                        this.removeDescription(rs.data.event_description);
                        Toast.fire({
                            icon: 'success',
                            title: 'Se ha eliminado con éxito la descripción.'
                        });
                    }
                })
                .catch(err => {
                    Toast.fire({
                        icon: 'error',
                        title: 'Ha ocurrido un error.'
                    }).then(rs => window.location.reload());
                });
        },

        removeDescription: function (eventDescription) {
            for (let i = 0; i < this.descriptions.length; i++) {
                const description = this.descriptions[i];
                if (description.id == eventDescription.id) {
                    this.descriptions.splice(i, 1);
                    return;
                }

            }
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

        validateDataEvent: function () {
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

            if (this.capacity <= 0) {
                Toast.fire({
                    icon: 'warning',
                    title: 'La capacidad del Evento debe ser mayor a 0.'
                })
                return false;
            }


            if (!this.isTheDateAfterNow(this.date)) {
                Toast.fire({
                    icon: 'warning',
                    title: "La fecha del evento debe ser posterior a la fecha actual"
                })
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



    },
    watch: {
        capacity: function (newQuestion, oldQuestion) {
            if (newQuestion < 0) {
                this.capacity = 0;
                alert("La cantidad no puede ser menor a 0");
            }
        }

    }
})
