<!doctype html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Пользовательский интерфейс</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue@2.7.8/dist/vue.js"></script>
	<!--<script src="https://cdn.jsdelivr.net/npm/vue@2.7.8"></script>-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js" integrity="sha512-odNmoc1XJy5x1TMVMdC7EMs3IVdItLPlCeL5vSUPN2llYKMJ2eByTTAIiiuqLg+GdNr9hF6z81p27DArRFKT7A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<link rel="stylesheet"
	      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
</head>
<body>
	<div id="wsocket"></div>
	<div class="container" id="user">
		<div class="col-lg-4 offset-lg-4 bg-secondary">
			<h3 class="text-white text-center mb-4" v-text="MenuHeaderText"></h3>
		</div>
		<div class="row justify-content-between bg-white mt-4">
			<div class="col-auto"  v-for="(but, index) in MenuButtons">
				<button class="btn btn-sm btn-outline-success" v-text="but.name" v-on:click="setAction(index)" v-if="CurrentActiveAction_ind!==index"></button>
				<button class="btn btn-sm btn-primary" v-text="but.name" v-else></button>
			</div>
		</div>
		<div class="container mt-5">
			<!-- templates RealTimeView-->
			<template v-if="CurrentActiveAction_ind == 0">
				<div class="container bg-info">
					<h4 class="text-center text-white">Фильтрация</h4>
					<div class="row justify-content-between mb-4">
						<div class="col-lg-3 mb-4">
							<label>Service</label>
							<select class="form-select mb-2" v-model.trim="Filters.project_name">
								<option value="null" selected>Все</option>
								<option v-for="item in ProjectsFilterArray" v-text="item.project_name" :value="item.project_name"></option>
							</select>
						</div>
						<div class="col-lg-3 mb-4">
							<label>Title</label>
							<input type="search" v-model.trim="Filters.title" class="form-control">
						</div>
						<div class="col-lg-3 mb-4">
							<label>Part</label>
							<select class="form-select mb-2" v-model.trim="Filters.part">
								<option value="null" selected>Все</option>
								<option v-for="item in ProjectPartArray" v-text="item.name" :value="item.value"></option>
							</select>
						</div>
						<div class="col-lg-3 mb-4">
							<label>Status</label>
							<select class="form-select mb-2" v-model.trim="Filters.status">
								<option value="null" selected>Все</option>
								<option v-for="item in ProjectStatusArray" v-text="item.name" :value="item.value"></option>
							</select>
						</div>
					</div>
				</div>
				<logs_table :actual-list-logs="ActualListLogs" :filters="Filters"></logs_table>
			</template>
			<template v-if="CurrentActiveAction_ind == 1">
				<p>Условия выборки</p>
				<logs_table :actual-list-logs="ActualListLogsDB" :filters="Filters"></logs_table>
			</template>
			<template v-if="CurrentActiveAction_ind ==2">
				<div class="row justify-content-center">
					<div class="col-lg-4">
						<div class="card">
							<form onsubmit="UserApp.changePassword(); return false">
								<div class="card-header">
							<h4 class="text-center">
							Смена пароля
								<span role="button" v-on:click="ChandeInputMod" v-if="inputsMode=='password'"><i class="fa-solid fa-eye-slash"></i></span>
								<span role="button" v-on:click="ChandeInputMod" v-else>	<i class="fa-solid fa-eye"></i></span>
							</h4>
							<template v-if="PasswordResults!==null">
								<div :class="'container '+PasswordResults.classs+' text-center'"><p v-text="PasswordResults.text"></p>
									<button class="btn btn-outline-dark mb-3" type="reset" v-on:click="clearForm" v-if="PasswordResults.classs =='bg-success'">Вот и хорошо</button>
								</div>
							</template>
						</div>
								<div class="card-body">
									<label>Ваш текущий пароль</label>
									<input :type="inputsMode" class="form-control mb-3" v-model.trim="currentPassword" placeholder="Ваш текущий пароль" name="currentPassword" required/>
									<label>Ваш новый пароль</label>
									<input :type="inputsMode" class="form-control mb-3" v-model.trim="newPassword" placeholder="Ваш новый пароль" name="newPassword" required/>
									<label>Новый пароль еще раз</label>
									<input :type="inputsMode" class="form-control mb-3" v-model.trim="confirmPassword" placeholder="Ваш новый пароль" name="confirmPassword" required/>
								</div>
								<div class="card-footer">
							<div class="row justify-content-end">
								<div class="col-4">
									<button class="btn btn-success" type="submit">Сменить</button>
								</div>
							</div>
						</div>
						</form>
					</div>
					</div>
				</div>
			</template>
		</div>
		<div class="modal fade" id="InfoLog" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Информация по записи лога</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
					</div>
					<div class="modal-body" v-html="FullLogInfoBody"></div>
					<div class="modal-footer">
						<template v-if="ShareBlock==true">
							<div class="container">
								<div class="row justify-content-end">
									<span class="text-end" role="button" v-on:click="disableShare" title="Отменить отправку">X</span>
								</div>
								<label>Выбрать получателя</label>
								<select v-model="Recipient" class="form-select mt-2 mb-4">
									<option value="All" selected>Все</option>
									<option v-for="us in Users" :value="us.user_telegram_id" v-text="us.user_name"></option>
								</select>
								<label>Комментарий</label>
								<textarea v-model="LogComment" rows="6" class="form-control"></textarea>
							</div>
						</template>
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cls_btn_hook">Закрыть</button>
						<button type="button" class="btn btn-primary" v-on:click="setShare" v-text="ShareBTNText"></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="./front_app/user_table.js"></script>
	<script>
		addEventListener("submit", function(event) {
			event.preventDefault();
		}, true);
	</script>
	<script>
		var Connect = new Vue({
			el:"#wsocket",
			data:{
				Socket: null,
				ToSay:null
			},
			methods:{
				connecttowss: function(pathToSRV,userIdentity){
					const self = this;
					self.Socket =  new WebSocket('wss://'+pathToSRV+':27800?user='+userIdentity),
							self.Socket.onmessage = function (event) {
								let income = JSON.parse(event.data)
								if(income.action=='Ping') {
									let messPong={ 'action':'Pong' }
									self.Socket.send(JSON.stringify(messPong))
									if(self.ToSay!==null){
										let message = {'action':self.ToSay}
										self.Socket.send(JSON.stringify(message))
									}
								}
								else{UserApp.receiverMethod(income)}
							};
				},
			},
			
		})
	</script>
	<script>
		<!-- todo add in RealTime in head of list websocket message NEWLOG + check lenght-->
		const UserApp = new Vue({
			el:'#user',
			data:{
				ActiveLogID : 0,
				CurrentActiveAction_ind:null,
				PathToServer:null,
				UserIdentity:null,
				MenuButtons:null,
				MenuHeaderText:null,
				ActualListLogs:null,
				ActualListLogsDB:null,
				FullLogInfoBody:null,
				ProjectsFilterArray:null,
				ProjectPartArray:null,
				ProjectStatusArray:null,
				Users:null,
				ShareBlock:false,
				Recipient:null,
				LogComment:null,
				ShareBTNText:'Оповестить',
				Filters:{
					project_name:'null',
					title:'',
					part:'null',
					status:'null',
					
				},
				inputsMode:'password',
				PasswordResults:null,
				currentPassword:null,
				newPassword:null,
				confirmPassword:null
			},
			methods:{
				clearForm(){
					this.PasswordResults = null
					this.currentPassword = null
					this.newPassword = null
					this.confirmPassword = null
				},
				ChandeInputMod()
				{if(this.inputsMode == "password"){this.inputsMode ='text'}else{this.inputsMode = 'password'}},
				changePassword(){
					this.PasswordResults = null
					if(this.newPassword!==this.confirmPassword){
						this.PasswordResults = {
							classs:'bg-danger',
							text:'Ваши новые пароли не совпадают'
						}
					}
					else{
						let Form = {password:this.currentPassword,newPassword:this.newPassword}
						axios.post('/user/setNewPassword',Form).then(res=>{
							this.PasswordResults = res.data
						})
					}
				},
				disableShare()
				{
					this.Recipient = null
					this.ShareBTNText = 'Оповестить'
					this.ShareBlock = false
				},
				setShare()
				{
					if(this.ShareBlock == false){
						this.ShareBlock = true
						this.ShareBTNText = 'Отправить'
					}
					else{ // Отправка
						let Form = {log_id:this.ActiveLogID,recipient:this.Recipient,comment:this.LogComment}
						axios.post('/user/sendAlarm',Form).then(res=>{
							this.Recipient = null
							this.LogComment = null
							this.ShareBTNText = 'Оповестить'
							this.ShareBlock = false
						})
						
					}
				},
				getFullLogInfo(log_id){
					axios.get('/user/getLogInfoByID?id='+log_id).then(res=>{
						this.FullLogInfoBody = res.data
						this.ActiveLogID = log_id
					})
					var myModal = new bootstrap.Modal(document.getElementById('InfoLog'), {
						keyboard: false
					})
					myModal.toggle();
				},
				getMainMenu() {
					axios.get('/user/mainMenu').then(res=>{
						this.MenuButtons = res.data.MenuButtons
						this.MenuHeaderText = res.data.MenuHeaderText
						this.PathToServer = res.data.PathToServer
						this.UserIdentity = res.data.UserIdentity
						Connect.connecttowss(this.PathToServer,this.UserIdentity)
					})
				},
				setAction(ind){
				 this.CurrentActiveAction_ind=ind
					if(this.MenuButtons[ind].action == 'real' && this.ActualListLogs == null){
						axios.get('/user/getLastLogs').then(res=>{
							this.ActualListLogs = res.data.list
							this.ProjectsFilterArray = res.data.projects
							this.ProjectPartArray = res.data.parts
							this.ProjectStatusArray = res.data.statuses
							this.Users = res.data.users
						})
					}
				},
				receiverMethod(data)
				{
					console.log(data)
				}
			},
			mounted(){
				this.getMainMenu()
				this.$root.$on('get_info',function(id){this.getFullLogInfo(id)});
				
			}
		});
	</script>
</body>
</html>
