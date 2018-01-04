<template>
    <div>
    <navbar></navbar>
      <div class="container" v-if="this.token">
        <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Form Halaman</div>
                        <div class="panel-body">
                            <form @submit.prevent="saveForm" @keydown="$event.keyCode === 13 ? $event.preventDefault() : false">
                              <div class="form-group" :class="{ 'has-error': errors.judul }">
                                <label for="upc_ean_isbn">Judul <span class="text-danger">*</span></label>
                                <input type="text" v-model="halaman.judul" class="form-control" id="judul" placeholder="judul" autofocus>
                                <div class="help-block" v-if="errors.judul">
                                  <div v-for="error in errors.judul"><strong>{{ error }}</strong></div>
                                </div>
                              </div>
                              <div class="form-group" :class="{ 'has-error': errors.deskripsi }">
                                <label for="item_name">Deskripsi <span class="text-danger">*</span></label>
                                <quill-editor ref="myTextEditor"
                                        v-model="halaman.deskripsi"
                                        :options="editorOption"
                                        @blur="onEditorBlur($event)"
                                        @focus="onEditorFocus($event)"
                                        @ready="onEditorReady($event)">
                                </quill-editor>
                                <div class="help-block" v-if="errors.deskripsi">
                                  <div v-for="error in errors.deskripsi"><strong>{{ error }}</strong></div>
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="size">Meta Deskripsi</label>
                                <input type="text" v-model="halaman.meta" class="form-control" id="meta" placeholder="meta deskripsi">
                              </div>
                              <div class="text-right">
                                <button type="submit" class="btn btn-primary" v-if="!edit">Save</button>
                              </div>
                            </form>
                            <div class="text-right">
                            <button class="btn btn-warning" @click="clearForm()" v-if="edit">Cancel</button>
                            <button class="btn btn-info" v-if="edit" @click="editForm(id)">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
              <div class="col-md-6">
                <div class="panel panel-default">
                <div class="panel-heading">List Halaman</div>
                  <div class="panel-body">
                      <div class="form-group form-float">
                        <div id="imaginary_container">
                            <div class="input-group stylish-input-group">
                                <input type="text" class="form-control"  placeholder="Search" v-model="pencarian" >
                                <span class="input-group-addon">
                                    <button type="submit">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </span>
                              </div>
                       </div>
                      </div>
                    <!-- table -->
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Judul</th>
                          <th>Slug</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody v-if="halamen.length" class="data-ada">
                        <tr v-for="row in halamen">
                          <td>{{row.judul}}</td>
                          <td>{{row.slug}}</td>
                          <td width="130px">
                            <button class="btn btn-xs btn-warning" @click="showHalaman(row.id)"><icon name="pencil" label="Forked Repository"></icon></button>
                            <button class="btn btn-xs btn-danger" v-bind:id="'delete-' + row.id" @click="deleteHalaman(row.id, index,row.judul)"><icon name="trash" label="Forked Repository"></icon></button>
                          </td>
                        </tr>
                      </tbody>
                      <tbody class="data-tidak-ada" v-else>
                        <tr>
                          <td colspan="5" class="text-center">Tidak Ada Data</td>
                        </tr>
                      </tbody>
                    </table>
                    <!-- .end table -->
                    <vue-simple-spinner v-if="loading"></vue-simple-spinner>
                    <div class="body" align="right">
                      <vue-pagination :data="halamenData" v-on:pagination-change-page="getHalaman" :limit="3"></vue-pagination>
                    </div>
                  </div>

                </div>
              </div>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from 'vue'
import 'vue-awesome/icons/flag'
import 'vue-awesome/icons'
import Icon from 'vue-awesome/components/Icon'
Vue.component('icon', Icon)
import axios from 'axios';
    export default {
      components: {
          navbar: require('./Navbar.vue'),
          Icon
      },
      data: function () {
        return {
          errors: [],
          halaman: {
            id: '',
            judul: '',
            deskripsi: '',
            meta:''
          },
          halamen: [],
          halamenData: {},
          editorOption: {
              modules: {
                toolbar: [
                  ['bold', 'italic', 'underline', 'strike'],
                  ['blockquote', 'code-block'],
                  [{ 'header': 1 }, { 'header': 2 }],
                  [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                  [{ 'script': 'sub' }, { 'script': 'super' }],
                  [{ 'indent': '-1' }, { 'indent': '+1' }],
                  [{ 'direction': 'rtl' }],
                  [{ 'size': ['small', false, 'large', 'huge'] }],
                  [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                  [{ 'font': [] }],
                  [{ 'color': [] }, { 'background': [] }],
                  [{ 'align': [] }],
                  ['clean'],
                  ['link', 'image', 'video']
                ],
                syntax: {
                  highlight: text => hljs.highlightAuto(text).value
                }
              }
          },
          url : window.location.origin + window.location.pathname,
          token : localStorage.getItem('token'),
          loading: true,
          pencarian: '',
          results: [],
          edit: false
        }
      },
      mounted() {
        //do something after mounting vue instance
          if (this.token ===null){
              this.$router.replace('/login')
          }
          else {
              var app = this;
              app.getHalaman();
          }
      },
      watch: {
            // whenever question changes, this function will run
            pencarian: function (q) {
              if (q != '') {
                this.searchHalaman()
              }
              else {
                this.getHalaman()
              }

            }
          },
      methods: {
        onEditorBlur(quill) {
            console.log('editor blur!', quill)
          },
          onEditorFocus(quill) {
            console.log('editor focus!', quill)
          },
          onEditorReady(quill) {
            console.log('editor ready!', quill)
          },
          onEditorChange({ quill, html, text }) {
            console.log('editor change!', quill, html, text)
            this.halaman.isi = html
          },
        clearForm() {
           this.edit = false
           this.halaman.judul = '',
           this.halaman.deskripsi='',
           this.halaman.meta=''
         },
        getHalaman(page) {
          var app = this;
          if (typeof page === 'undefined'){
            page = 1;
          }
          axios.get('/api/gethalaman?page='+page)
          .then(function (resp){
            app.halamen = resp.data.data;
            app.halamenData = resp.data;
            app.loading = false;
          })
          .catch(function (resp){
            console.log(resp);
            app.loading = false;
            alert("Could not load halaman");
          });
        },
        searchHalaman(page){
          var app = this;
          if (typeof page === 'undefined') {
            page = 1;
          }
          axios.get('/api/searchhalaman?q='+app.pencarian+'&page='+page)
          .then(function (resp) {
            app.halamen = resp.data;
            app.halamenData = [];
          })
          .catch(function (resp) {
            console.log(resp);
            alert("Could not load halaman");
          });
        },
        alert(pesan){
          this.$swal({
            title: "Berhasil Menghapus halaman !",
            text: pesan,
            icon:"success",
          });
        },
        deleteHalaman(id, index,judul){
          if(confirm("Yakin Ingin Menghapus Halaman "+judul+" ?")){
            var app = this;
            axios.delete('/api/halaman/' +id+'?token='+this.token)
            .then(function (resp) {
              app.getHalaman();
              app.alert("Berhasil Menghapus halaman "+ judul)
            })
            .catch(function (resp){
              alert("Could not delete menu");
            });
          }
        },
        saveForm() {
          var app = this;
          var newHalaman = app.halaman;
          axios.post('/api/halaman?token='+ this.token, newHalaman)
          .then(function(resp){
            app.message = 'Sukses : Berhasil Menambah halaman ' +
            app.halaman.judul;
            app.alert(app.message);
            app.clearForm(),
            app.getHalaman();
            app.errors ='';
          })
          .catch(function (resp){
            app.success = false;
            app.errors = resp.response.data.errors;
          });
        },
        editForm() {
          var app = this;
          var newHalaman = app.halaman;
          axios.put('/api/halaman/' + app.halamanId+'?token='+this.token, newHalaman)
          .then(function(resp){
            app.message = 'Sukses : Berhasil Ubah halaman ' +
            app.halaman.judul;
            app.alert(app.message);
            app.clearForm(),
            app.getHalaman();
            app.edit = false
            app.errors ='';
          })
          .catch(function (resp){
            app.success = false;
            app.errors = resp.response.data.errors;
          });
        },
        showHalaman(id) {

          let app = this;
          app.halamanId = id;
          axios.get('/api/edithalaman/' + id +'?token='+this.token)
            .then(function(resp) {
                app.edit = true;
              app.halaman.id = resp.data.id;
              app.halaman = resp.data;
            })
            .catch(function() {
              alert("Could not load your halaman")
            });
        },
        alert(pesan){
          this.$swal({
            title: "Berhasil !",
            text: pesan,
            icon: "success",
          });
        }
      }

    }
</script>
<style>
    .stylish-input-group .input-group-addon{
      background: white !important;
    }
    .stylish-input-group .form-control{
    border-right:0;
    box-shadow:0 0 0;
    border-color:#ccc;
    }
    .stylish-input-group button{
      border:0;
      background:transparent;
    }
</style>
