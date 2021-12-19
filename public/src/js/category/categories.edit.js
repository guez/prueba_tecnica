var app_categories_edit = new Vue({
    el: '#app-edit-categories',
    delimiters: ['${', '}'],
    data: {
        //urls
        urlCreateDescription: '',
        urlUpdateDescription: '',
        urlDeleteDescription: '',
        urlRedirect: '',

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
        category_id: 0,
        newDescription: {
            name: "",
            language: ""
        }
    },
    methods: {
        setUrlCreateDescription: function (url) {
            this.urlCreateDescription = url;
            this.urlDeleteDescription = url;
            this.urlUpdateDescription = url;
        },


        setUrlRedirect: function (url) {
            this.urlRedirect = url;
        },


        loadData: function (fields) {
            console.log(fields);
            this.slug = fields.slug;
            this.category_id = fields.id;
            fields.descriptions.forEach(description => {
                this.addDescription(description);
            });
            this.newDescription.category_id = this.category_id;
        },

        addDescription: function (categoryDescription) {
            this.descriptions.push({
                id: categoryDescription.id,
                name: categoryDescription.name,
                language: categoryDescription.language,
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

        createNewDescription: function () {
            if (this.newDescription.name == "") {
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
                                title: "Ha ocurrido un error."
                            }).then(rs => window.location.reload());
                            return;
                        }

                        if (rs.data.type_event == "CREATE") {
                            this.addDescription(rs.data.category_description);
                            Toast.fire({
                                icon: 'success',
                                title: 'Se ha creado la nueva descripción con éxito.'
                            })
                            return;
                        }

                        if (rs.data.type_event == "UPDATE") {
                            this.updateDescription(rs.data.category_description);
                            Toast.fire({
                                icon: 'success',
                                title: 'Se ha detectado el lenguaje ingresado, además se actualizó.'
                            });
                        }

                        if (rs.data.type_event == "RESTORE") {
                            this.addDescription(rs.data.category_description);
                            Toast.fire({
                                icon: 'success',
                                title: 'Se ha detectado el lenguaje ingresado, se ha restaurado y se actualizó correctamente.'
                            });
                        }

                        this.newDescription.name = "";
                        this.newDescription.language = "";

                    }

                })
                .catch(err => this.setError(err));
        },

        updateDescription: function (categoryDescription) {
            this.descriptions.forEach(description => {
                if (description.language == categoryDescription.language) {
                    description.name = categoryDescription.name;
                }
            });
        },

        saveDescription: function (idDescription) {
            let description = this.getDescription(idDescription);

            if (description == null) return;

            description.isEditing = false;
            let formData = {
                language: description.language,
                name: this.tempDescription,
            };

            axios.put(this.urlUpdateDescription + '/' + description.category_id, formData)   // AQUI ME QUEDE
                .then(rs => {
                    let data = rs.data;
                    if (data.state) {
                        if (data.state != true) {
                            Toast.fire({
                                icon: 'error',
                                title: "Ha ocurrido un error."
                            }).then(rs => window.location.reload());
                            return;
                        }


                        this.updateDescription(rs.data.category_description);
                        Toast.fire({
                            icon: 'success',
                            title: 'Se ha detectado el lenguaje ingresado y se actualizó correctamente.'
                        });
                        this.tempDescription = "";
                    }

                })
                .catch(err => this.setError(err));
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
                        if (rs.data.category.descriptions_count > 1) {
                            this.removeDescription(rs.data.category_description);
                            Toast.fire({
                                icon: 'success',
                                title: 'Se ha eliminado con éxito la descripción.'
                            });
                        } else {
                            Toast.fire({
                                icon: 'success',
                                title: 'Se ha eliminado con éxito la descripción y la categoría.'
                            }).then(rs => window.location.href = this.urlRedirect);
                        }
                    }
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
        },

        removeDescription: function (categoryDescription) {
            for (let i = 0; i < this.descriptions.length; i++) {
                const description = this.descriptions[i];
                if (description.id == categoryDescription.id) {
                    this.descriptions.splice(i, 1);
                    return;
                }

            }
        },

        getDescription: function (idDescription) {
            let descriptionSelect = null;
            this.descriptions.forEach(description => (description.id == idDescription) ? descriptionSelect = description : "");
            return descriptionSelect;
        },

        setError: function (err) {
            let errors = err.response.data.errors;
            if (errors != undefined) {
                Object.keys(errors).forEach(keyError => {
                    Toast.fire({
                        icon: 'error',
                        title: errors[keyError]
                    })
                });
            }
        }


    }
})
