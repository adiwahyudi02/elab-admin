<template>
    <div class="d-flex justify-content-start">
        <nav id="sidebar" class="sidebar">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand" href="index.html">
                    <span class="align-middle">E-lab</span>
                </a>
				<ul class="sidebar-nav">
					<li class="sidebar-header">
						Pages
					</li>

					<li class="sidebar-item">
                        <router-link class="sidebar-link" to="/">
                            <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
                        </router-link>
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" @click="redirectUpdateProfile">
                            <i class="align-middle" data-feather="user"></i> <span class="align-middle">Profile</span>
                        </a>
					</li>
					<li class="sidebar-header">
						Tools & Components
					</li>
					<li class="sidebar-item">
						<a href="#ui" data-toggle="collapse" class="sidebar-link collapsed">
                            <i class="align-middle" data-feather="briefcase"></i> <span class="align-middle">Masters</span>
                        </a>
						<ul id="ui" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
							<li class="sidebar-item"><router-link class="sidebar-link" to="/labs">Labs</router-link></li>
                            <li class="sidebar-item"><router-link class="sidebar-link" to="/class">Class</router-link></li>
                            <li class="sidebar-item"><router-link class="sidebar-link" to="/computers">Computers</router-link></li>
                            <li class="sidebar-item"><router-link class="sidebar-link" to="/subjects">Subjects</router-link></li>
                            <li class="sidebar-item"><router-link class="sidebar-link" to="/students">Students</router-link></li>
                            <li class="sidebar-item"><router-link class="sidebar-link" to="/billings">Billings</router-link></li>
                            <li class="sidebar-item"><router-link class="sidebar-link" to="/teachers">Teachers</router-link></li>
                            <li class="sidebar-item"><router-link class="sidebar-link" to="/admins">Admins</router-link></li>
							<li class="sidebar-item"><router-link class="sidebar-link" to="/tasks">Tasks</router-link></li>
						</ul>
					</li>
                    <li class="sidebar-item">
						<a href="#trash" data-toggle="collapse" class="sidebar-link collapsed">
                            <i class="align-middle" data-feather="briefcase"></i> <span class="align-middle">Trash</span>
                        </a>
						<ul id="trash" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
							<li class="sidebar-item"><router-link class="sidebar-link" to="/trash-labs">Labs</router-link></li>
                            <li class="sidebar-item"><router-link class="sidebar-link" to="/trash-class">Class</router-link></li>
                            <li class="sidebar-item"><router-link class="sidebar-link" to="/trash-computers">Computers</router-link></li>
                            <li class="sidebar-item"><router-link class="sidebar-link" to="/trash-subjects">Subjects</router-link></li>
                            <li class="sidebar-item"><router-link class="sidebar-link" to="/trash-students">Students</router-link></li>
                            <li class="sidebar-item"><router-link class="sidebar-link" to="/trash-billings">Billings</router-link></li>
                            <li class="sidebar-item"><router-link class="sidebar-link" to="/trash-teachers">Teachers</router-link></li>
                            <li class="sidebar-item"><router-link class="sidebar-link" to="/trash-admins">Admins</router-link></li>
							<li class="sidebar-item"><router-link class="sidebar-link" to="/trash-tasks">Tasks</router-link></li>
						</ul>
					</li>
					<li class="sidebar-item">
						<a href="#schedule" data-toggle="collapse" class="sidebar-link collapsed">
                            <i class="align-middle" data-feather="briefcase"></i> <span class="align-middle">Schedule</span>
                        </a>
						<ul id="schedule" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
							<li class="sidebar-item"><router-link class="sidebar-link" to="/list-schedule">Lab Schedule</router-link></li>
						</ul>
					</li>
				</ul>      
			</div>
		</nav>
        <div style="width: 100%">
            <nav class="navbar navbar-expand navbar-light navbar-bg">
				<a class="sidebar-toggle d-flex" @click="sidebar">
          <i class="hamburger align-self-center"></i>
        </a>

				<form class="form-inline d-none d-sm-inline-block">
					<div class="input-group input-group-navbar">
						<input type="text" class="form-control" placeholder="Search…" aria-label="Search">
						<div class="input-group-append">
							<button class="btn" type="button">
                <i class="align-middle" data-feather="search"></i>
              </button>
						</div>
					</div>
				</form>

				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="bell"></i>
									<span class="indicator">4</span>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right py-0" aria-labelledby="alertsDropdown">
								<div class="dropdown-menu-header">
									4 New Notifications
								</div>
								<div class="list-group">
									<a href="#" class="list-group-item">
										<div class="row no-gutters align-items-center">
											<div class="col-2">
												<i class="text-danger" data-feather="alert-circle"></i>
											</div>
											<div class="col-10">
												<div class="text-dark">Update completed</div>
												<div class="text-muted small mt-1">Restart server 12 to complete the update.</div>
												<div class="text-muted small mt-1">30m ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row no-gutters align-items-center">
											<div class="col-2">
												<i class="text-warning" data-feather="bell"></i>
											</div>
											<div class="col-10">
												<div class="text-dark">Lorem ipsum</div>
												<div class="text-muted small mt-1">Aliquam ex eros, imperdiet vulputate hendrerit et.</div>
												<div class="text-muted small mt-1">2h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row no-gutters align-items-center">
											<div class="col-2">
												<i class="text-primary" data-feather="home"></i>
											</div>
											<div class="col-10">
												<div class="text-dark">Login from 192.186.1.8</div>
												<div class="text-muted small mt-1">5h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row no-gutters align-items-center">
											<div class="col-2">
												<i class="text-success" data-feather="user-plus"></i>
											</div>
											<div class="col-10">
												<div class="text-dark">New connection</div>
												<div class="text-muted small mt-1">Christina accepted your request.</div>
												<div class="text-muted small mt-1">14h ago</div>
											</div>
										</div>
									</a>
								</div>
								<div class="dropdown-menu-footer">
									<a href="#" class="text-muted">Show all notifications</a>
								</div>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown" data-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="message-square"></i>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right py-0" aria-labelledby="messagesDropdown">
								<div class="dropdown-menu-header">
									<div class="position-relative">
										4 New Messages
									</div>
								</div>
								<div class="list-group">
									<a href="#" class="list-group-item">
										<div class="row no-gutters align-items-center">
											<div class="col-2">
												<img src="/img/avatars/avatar-5.jpg" class="avatar img-fluid rounded-circle" alt="Vanessa Tucker">
											</div>
											<div class="col-10 pl-2">
												<div class="text-dark">Vanessa Tucker</div>
												<div class="text-muted small mt-1">Nam pretium turpis et arcu. Duis arcu tortor.</div>
												<div class="text-muted small mt-1">15m ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row no-gutters align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-2.jpg" class="avatar img-fluid rounded-circle" alt="William Harris">
											</div>
											<div class="col-10 pl-2">
												<div class="text-dark">William Harris</div>
												<div class="text-muted small mt-1">Curabitur ligula sapien euismod vitae.</div>
												<div class="text-muted small mt-1">2h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row no-gutters align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-4.jpg" class="avatar img-fluid rounded-circle" alt="Christina Mason">
											</div>
											<div class="col-10 pl-2">
												<div class="text-dark">Christina Mason</div>
												<div class="text-muted small mt-1">Pellentesque auctor neque nec urna.</div>
												<div class="text-muted small mt-1">4h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row no-gutters align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-3.jpg" class="avatar img-fluid rounded-circle" alt="Sharon Lessman">
											</div>
											<div class="col-10 pl-2">
												<div class="text-dark">Sharon Lessman</div>
												<div class="text-muted small mt-1">Aenean tellus metus, bibendum sed, posuere ac, mattis non.</div>
												<div class="text-muted small mt-1">5h ago</div>
											</div>
										</div>
									</a>
								</div>
								<div class="dropdown-menu-footer">
									<a href="#" class="text-muted">Show all messages</a>
								</div>
							</div>
						</li>
                        <li class="nav-item">
                            <b-nav-item-dropdown bottom size="sm" class="p-0">
                                <template v-slot:button-content class="p-0">
                                    <em>
                                        <a class="nav-link p-0 d-none d-sm-inline-block" href="#">
                                            <img src="../assets/img/avatars/avatar.jpg" class="avatar img-fluid rounded mr-1" alt="Admin" /> <span class="text-dark">{{user.nama}}</span>
                                        </a>
                                    </em>
                                </template>
                                <b-dropdown-item @click="redirectUpdateProfile">
                                    <a class="dropdown-item p-0" href="pages-profile.html"><i class="" data-feather="user"></i> Profile</a>
                                    <div class="dropdown-divider"></div>
                                </b-dropdown-item>
                                <b-dropdown-item href="#" class="p-0">
                                    <router-link class="dropdown-item p-0" to="/logout">
                                        Log out
                                    </router-link>
                                </b-dropdown-item>
                            </b-nav-item-dropdown>
							<b-modal id="profile" :hide-footer="true" >
								<b-form @submit.stop.prevent="update">
									<div>
										<div class="form-group">
											<label for="name">Name:</label>
											<input type="text" v-model.trim="$v.user.nama.$model" id="name" class="form-control mb-0">
											<div class="error" v-if="!$v.user.nama.required && $v.user.nama.$anyDirty">Name is required.</div>
										</div>
									</div>

									<div>
										<div class="form-group">
											<label for="Username">Username:</label>
											<input type="text" v-model.trim="$v.user.username.$model" id="Username" class="form-control mb-0">
											<div class="error" v-if="!$v.user.username.required && $v.user.username.$anyDirty">Username is required.</div>
										</div>
									</div>

									<div>
										<div class="form-group">
											<label for="email">Email:</label>
											<input type="text" v-model.trim="$v.user.email.$model" id="email" class="form-control mb-0">
											<div class="error" v-if="!$v.user.email.required && $v.user.email.$anyDirty">Email is required.</div>
											<div class="error" v-if="!$v.user.email.isUnique && $v.user.email.$anyDirty">Email is already registered.</div>
										</div>
									</div>

									<div>
										<div class="form-group">
											<label for="password">Password</label>
											<div class="d-flex justify-content-start align-items-center">
												<div style="width: 95%">
													<input :type="passwordFieldType" class="form-control mb-0" id="password" v-model.trim="$v.user.password.$model"/>
												</div>
												<div>
													<b-button class="btn btn-sm py-1 px-2 rounded" @click="switchVisibility">
														<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye align-middle"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
													</b-button>
												</div>
											</div>
											<div class="error" v-if="!$v.user.password.required && $v.user.password.$anyDirty">Password is required.</div>
											<div class="error" v-if="!$v.user.password.minLength">Password must have at least {{ $v.user.password.$params.minLength.min }} letters.</div>
										</div>
									</div>
							
									<div>
										<b-button type="submit" class="btn btn-info btn-sm px-5">
											<div v-if="isLoadingAction">
												<b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
												<b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
												<b-spinner variant="light" type="grow" label="Spinning" small></b-spinner>
											</div>
											<div v-else>
												Save
											</div>
										</b-button>
									</div>
								</b-form>
								<b-toast id="toast-updated" title="Profile successfuly to updated." variant="success" toaster="b-toaster-bottom-right">
									Row will be updated.
								</b-toast>
							</b-modal>
                        </li>
					</ul>
				</div>
			</nav>
            <slot>
            </slot>
        </div>
    </div>
</template>

<script>

import { required, minLength } from 'vuelidate/lib/validators'
import { mapState  } from 'vuex'
import $ from "jquery";

export default {
    data() {
        return {
			user: '',
			pre_email: '',
			passwordFieldType: 'password'
        }
	},
	computed: {
        ...mapState({
            admin: state => state.admin.items,
            isLoadingAction: state => state.isLoadingAction
        })
    },
	validations: {
        user: {
            nama: {required},
			email: {
				required,
				isUnique (value) {

					if (this.pre_email == value) {
						return true  
					}else{
						if (value === '') return true
		
						if (this.admin.find(user => user.email === value)) {
							return false
						}
						else{
							return true
						}
					}
                }   
			},
            username: {
                required  
            },
            password: {
                required,
                minLength: minLength(6)
            },
		}
	},
	methods: {
		sidebar(){
			$(".sidebar")
			.toggleClass("collapsed")
			// Triger resize after animation
			.one("transitionend", function() {
				setTimeout(function() {
				window.dispatchEvent(new Event("resize"));
				}, 100);
			});
		},
		switchVisibility(){
			this.passwordFieldType = this.passwordFieldType === 'password' ? 'text' : 'password'
		},
		async redirectUpdateProfile(){
            try{
                await this.$store.commit('SET_ISLOADING_ACTION', true, { root: true })
                await this.$store.dispatch('admin/REFRESH_GET_ALL')
                await this.$store.commit('SET_ISLOADING_ACTION', false, { root: true })
                this.$bvModal.show( "profile")
            }catch(err) {
                alert(err);
            }
		},
		async update(){

            this.$v.user.$touch();

            if (this.$v.user.$anyError) {
                return;
            }

            try{
				await this.$store.dispatch('admin/UPDATE', this.user)
				
				let local = JSON.parse(localStorage.getItem('auth'));
                const data = {
                    'id': this.user.id,
                    'nama': this.user.nama,
                    "username" : this.user.username,
                    "email": this.user.email,
                    "password": this.user.password,
                    "role": this.user.role
                }
                localStorage.setItem('auth', JSON.stringify({
                    'data': data,
                    'token': local.token
				}));
				
				await this.$bvToast.show('toast-updated')
            }catch(err){
                alert(err);
            }
        }
	},
    async created(){
		try {

			let data = JSON.parse(localStorage.getItem('auth'));

			this.pre_email = data.data.email
			this.user = data.data

		} catch (error) {
			alert(error)
		}
        
    }
}
</script>

<style>

</style>