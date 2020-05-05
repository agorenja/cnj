<template>
    <div class="c-upload-form">
        <form @submit="submitForm" enctype="multipart/form-data" class="c-upload-form__form">
            <div class="c-upload-form__form-item">
                <input type="file" name="filename" id="inputFileUpload"
                       v-on:change="onFileChange" accept=".csv">
                <div class="input-group-append">

                </div>
            </div>
            <div class="c-upload-form__form-item">
                <input type="submit" class="btn btn-primary" value="Upload">
            </div>
            <div class="c-upload-form__form-item">
                <input type="checkbox" v-model="form.save" id="save">
                <label for="save">Save to database</label>
            </div>
            <div class="c-upload-form__form-result">
                <div class="c-upload-form__form-result-item" v-for="(result, key) in result">
                    <template v-if="key === 'group_by_year'">
                        <div><strong>Avg price per year in London area</strong></div>
                        <div v-for="(count, year) in result">
                            <div>{{year}}: {{count | round }}</div>
                        </div>
                    </template>
                    <template v-else>
                        <strong>{{ key }}: </strong> {{result | round}}
                    </template>
                </div>
            </div>
        </form>
        <div class="c-upload-form--loading" v-if="loading"/>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                form: {
                    file: '',
                    save: false,
                },
                result: {},
                loading: false,
            }
        },
        methods: {
            onFileChange (e) {
                this.form.file = e.target.files[0]
            },
            submitForm (e) {
                this.loading = true
                e.preventDefault()
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data',
                    },
                }

                let formData = new FormData()
                formData.append('file', this.form.file)
                formData.append('save', this.form.save)

                axios.post('/api/files', formData, config).then(({ data }) => {
                    this.result = data
                }).catch(function (error) {
                    // TODO: Show laravel validation errors
                }).finally(() => {
                    this.loading = false
                })
            },
        },
        filters: {
            round (item) {
                return Math.round(item)
            },
        },
    }
</script>

<style lang="scss">
    .c-upload-form {
        &__form-item {
            margin-bottom: 20px;
        }

        &--loading {
            position: absolute;
            top: 0;
            left: 0;
            background-color: azure;
            height: 100%;
            width: 100%;
            opacity: 0.5;
        }
    }
</style>
