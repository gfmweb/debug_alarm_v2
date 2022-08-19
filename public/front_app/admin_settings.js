addEventListener("submit", function(event) {
	event.preventDefault();
}, true);
const Settings = new Vue({
	el:'#admin_settings',
	data:{
		currentPassword:null,
		newPassword:null,
		confirmPassword:null,
		serviceMode:'start',
		inputstypes:'password',
		alertForm:null,
		passwordResults:null,
		hookPrefix:null,
		hookResult:null
	},
	methods:{
		redisInit(){
			axios.post('/admin/redisInit').then(res=>{alert('Redis ключи проинициализированы')})
		},
		dropPasswordErrors(){return this.passwordResults = null},
		changeInputTypes(){
			if(this.inputstypes == 'password'){return this.inputstypes='text'}
			else{return this.inputstypes = 'password'}
		},
		webHookSet(){
			const self = this
			var modal = document.getElementById('cls_btn_hook')
			modal.click()
			axios.post('/admin/setWebHook',{route:this.hookPrefix.current}).then(res=>{
				axios.get(res.data).then(result=>{
					self.hookResult = result.data
				})

			})
		},
		onOffService(){
			if(this.serviceMode=='start'){
				var conf = confirm('Вы уверены что хотите остановить сервис?')
				if(conf){
					this.serviceMode = 'stop'
					axios.post('/admin/changeServiceMode',{serviceMode:'stop'}).then(res=>{console.log(res.data)})
				}
			}
			else{
				console.log(this.serviceMode)
				this.serviceMode = 'start'
				axios.post('/admin/changeServiceMode',{serviceMode:'start'}).then(res=>{console.log(res.data)})
			}
		},
		setNewPassword(){
			const self = this
			if(this.newPassword!==this.confirmPassword){
				this.alertForm = {}
				this.alertForm.field = 'Поля нового пароля и его подтверждения ';
				this.alertForm.text = ' отличаются друг от друга'
			}
			else{axios.post('/admin/setNewPassword', {current: this.currentPassword,	password: this.newPassword}).then(res => {this.passwordResults = res.data
				if(self.passwordResults.background == 'bg-success'){
					self.currentPassword = null
					self.newPassword = null
					self.confirmPassword = null
				}
			})}
		},
		dropAlert(){this.alertForm = null},
		dropHook(){this.hookResult = null}
	},
	mounted(){
		axios.get('/admin/getServiceStatus').then(res=>{
			this.serviceMode = res.data
		})
		axios.get('/admin/getHookAddress').then(res=>{
			this.hookPrefix = res.data
		})
	}
});
