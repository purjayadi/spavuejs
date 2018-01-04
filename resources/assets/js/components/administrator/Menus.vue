<template>
    <div>
    <navbar></navbar>
      <div class="container" v-if="this.token">
        <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">Form Menu</div>
                        <div class="panel-body">
                            <form @submit.prevent="saveForm" @keydown="$event.keyCode === 13 ? $event.preventDefault() : false">
                              <div class="form-group" :class="{ 'has-error': errors.name }">
                                <label for="upc_ean_isbn">Name <span class="text-danger">*</span></label>
                                <input type="text" v-model="menu.name" class="form-control" id="name" placeholder="Menu" autofocus>
                                <div class="help-block" v-if="errors.name">
                                  <div v-for="error in errors.name"><strong>{{ error }}</strong></div>
                                </div>
                              </div>
                              <div class="form-group" :class="{ 'has-error': errors.name }">
                                <label for="item_name">order <span class="text-danger">*</span></label>
                                <input type="number" v-model="menu.order" class="form-control" id="order" placeholder="menu order" autofocus>
                                <div class="help-block" v-if="errors.order">
                                  <div v-for="error in errors.order"><strong>{{ error }}</strong></div>
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="size">Link</label>
                                <input type="text" v-model="menu.link" class="form-control" id="link" placeholder="link">
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
              <div class="col-md-8">
                <div class="panel panel-default">
                <div class="panel-heading">List Menu</div>
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
                          <th>Name</th>
                          <th>Order</th>
                          <th>Link</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody v-if="menus.length" class="data-ada">
                        <tr v-for="row in menus">
                          <td>{{row.name}}</td>
                          <td>{{row.order}}</td>
                          <td>{{row.link}}</td>
                          <td width="130px">
                            <button class="btn btn-xs btn-warning" @click="showMenu(row.id)"><icon name="pencil" label="Forked Repository"></icon></button>
                            <button class="btn btn-xs btn-danger" v-bind:id="'delete-' + row.id" @click="deleteMenu(row.id, index,row.name)"><icon name="trash" label="Forked Repository"></icon></button>
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
                      <vue-pagination :data="menusData" v-on:pagination-change-page="getMenus" :limit="3"></vue-pagination>
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
          menu: {
            id: '',
            name: '',
            order: '',
            link:''
          },
          menus: [],
          menusData: {},
          token: localStorage.getItem('token'),
          url : window.location.origin + window.location.pathname,
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
              app.getMenus()
          }
      },
      watch: {
            // whenever question changes, this function will run
            pencarian: function (q) {
              if (q != '') {
                this.searchMenu()
              }
              else {
                this.getMenus()
              }

            }
          },
      methods: {
        clearForm() {
           this.edit = false
           this.menu.name = '',
           this.menu.order='',
           this.menu.link=''
         },
        getMenus(page) {
          var app = this;
          if (typeof page === 'undefined'){
            page = 1;
          }
          // ?page='+page+'
          axios.get('/api/getmenu?page='+page)
          .then(function (resp){
            app.menus = resp.data.data;
            app.menusData = resp.data;
            app.loading = false;
          })
          .catch(function (resp){
            console.log(resp);
            app.loading = false;
            alert("Could not load menu");
          });
        },
        searchMenu(page){
          var app = this;
          if (typeof page === 'undefined') {
            page = 1;
          }
          axios.get('/api/searchmenu?q='+app.pencarian+'&page='+page)
          .then(function (resp) {
            app.menus = resp.data;
            app.menusData = [];
          })
          .catch(function (resp) {
            console.log(resp);
            alert("Could not load menu");
          });
        },
        alert(pesan){
          this.$swal({
            title: "Berhasil Menghapus Menu !",
            text: pesan,
            icon:"success",
          });
        },
        deleteMenu(id, index,name){
          if(confirm("Yakin Ingin Menghapus Halaman "+name+" ?")){
            var app = this;
            axios.delete('/api/menu/' +id+'?token=' + this.token)
            .then(function (resp) {
              app.getMenus();
              app.alert("Berhasil Menghapus Menu "+ name)
            })
            .catch(function (resp){
              alert("Could not delete menu");
            });
          }
        },
        saveForm() {
          var app = this;
          var newMenu = app.menu;
          axios.post('/api/menu?token=' + this.token, newMenu)
          .then(function(resp){
            app.message = 'Sukses : Berhasil Menambah Menu ' +
            app.menu.name;
            app.alert(app.message);
            app.clearForm(),
            app.getMenus();
            app.errors ='';
          })
          .catch(function (resp){
            app.success = false;
            app.errors = resp.response.data.errors;
          });
        },
        editForm() {
          var app = this;
          var newMenu = app.menu;
          axios.put('/api/menu/' + app.menuId + '?token=' + this.token, newMenu)
          .then(function(resp){
            app.message = 'Sukses : Berhasil Ubah Menu ' +
            app.menu.name;
            app.alert(app.message);
            app.clearForm(),
            app.getMenus();
            app.edit = false
            app.errors ='';
          })
          .catch(function (resp){
            app.success = false;
            app.errors = resp.response.data.errors;
          });
        },
        showMenu(id) {
          let app = this;
          app.menuId = id;
          axios.get('/api/editmenu/' + id+'?token=' + this.token)
            .then(function(resp) {
                app.edit = true;
              app.menu.id = resp.data.id;
              app.menu = resp.data;
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
