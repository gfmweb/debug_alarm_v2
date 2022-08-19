addEventListener("submit", function(event) {
	event.preventDefault();
}, true);
const telega = window.Telegram.WebApp;
const Connect = new Vue({
	el:"#wsocket",
	data:{
		Socket: null,
		ToSay:null
	},
	methods:{
		connecttowss: function(pathToSRV,userIdentity){
			const self = this;
			self.Socket =  new WebSocket('wss://'+pathToSRV+':27800?user='+userIdentity+'telegram'),
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

});
const UserApp = new Vue({
	el:'#user',
	data:{
		userInfo:null,
		userID:null,
		LifeTime:'',
		TotalLogs:'',
		OverHead:'',
		Presure:'',
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
		Query:{
			id:null,
			project_name:'null',
			volume:'',
			startdt:null,
			starts:'00',
			finishdt:null,
			finishs:'59'
		},
		inputsMode:'password',
		PasswordResults:null,
		currentPassword:null,
		newPassword:null,
		confirmPassword:null
	},
	methods:{
		getQueryDB(){
			let Form = {
				id:this.Query.id,
				project_name:this.Query.project_name,
				query:this.Query.volume,
				starttime:this.Query.startdt,
				startsec:this.Query.starts,
				finishtime:this.Query.finishdt,
				finishs:this.Query.finishs
			}
			axios.post('/user/LogDBQuery',Form).then(res=>{
				this.ActualListLogsDB = res.data
			})
		},
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
			axios.get('/telegram/mainMenu').then(res=>{
				this.MenuButtons = res.data.MenuButtons
				this.MenuHeaderText = res.data.MenuHeaderText
				this.PathToServer = res.data.PathToServer
				this.UserIdentity = res.data.UserIdentity
				Connect.connecttowss(this.PathToServer,this.UserIdentity)
			})
		},
		setAction(ind){
			this.CurrentActiveAction_ind=ind
		},
		receiverMethod(data)
		{
			if (typeof data.lifeTime !== 'undefined'){this.LifeTime = data.lifeTime}
			if (typeof data.TotalLogs !== 'undefined'){this.TotalLogs = data.TotalLogs}
			if (typeof data.counter !== 'undefined'){this.Presure = data.counter}
			if (typeof data.overHeadCounter !== 'undefined'){this.OverHead = data.overHeadCounter}
			if (typeof data.update !== 'undefined'){
				var flags = 'add'
				this.ActualListLogs.forEach(function(row){
					if(row.log_id == data.update.log_id){
						flags = 'skipp'
					}
				})
				if(flags == 'add'){
					this.ActualListLogs.unshift(data.update)
					let len = this.ActualListLogs.length
					if(len >100){
						this.ActualListLogs.pop()
					}
				}
			}
		}
	},
	mounted(){
		this.userInfo = telega.initDataUnsafe
		if(typeof this.userInfo.user!=="undefined") {
			const self = this
			this.userID = this.userInfo.user.id
			axios.post('/loginByTelegram',{id:this.userID}).then(res=>{
				if(res.data!=='close') {
					telega.expand()
					self.getMainMenu()
					self.$root.$on('get_info', function (id) {
						self.getFullLogInfo(id)
					});
					axios.get('/user/getLastLogs').then(res => {
						self.ActualListLogs = res.data.list
						self.ProjectsFilterArray = res.data.projects
						self.ProjectPartArray = res.data.parts
						self.ProjectStatusArray = res.data.statuses
						self.Users = res.data.users
					})
				}
				else{
					telega.close()
				}
			})


		}
	}
});
