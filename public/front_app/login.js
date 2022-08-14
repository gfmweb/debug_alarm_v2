const App = new Vue({
	el:'#login',
	data:{
		activeModeIndex:1, // Активная вкладка по умолчанию
		logMode:null, //Виды вкладок
		formOperationIndex:0, // Шаг начала работы с операциями формы
		formLoginData:null, // Массив полей и прочие свойства формы
		formOperations:null, // Возможные действия формы
		formErrors:null, // Login errors
		TelegramUserID:null
	},
	methods:{
		setActiveMode(index) //Меняет активность форм входа
		{
			this.activeModeIndex = index
			this.requestLoginFields()
		},
		requestLoginFields(){ //Запрашивает поля и действия для формы
			const FormDat = new FormData()
			if(this.logMode == null){
				FormDat.append('mode','logs')
			}
			else {
				FormDat.append('mode', this.logMode[this.activeModeIndex].mode)
			}
			const urI = '/login/getLoginForm'
			axios.post(urI,FormDat).then(res=>{
				this.formLoginData = res.data.forms
				this.logMode = res.data.login_variable
			})
		},
		processForm()
		{
			const urI = this.formLoginData.action[this.formOperationIndex]
			var forma = document.forms[0]
			const fieldsCount =  forma.elements.length-1
			const FormDat = {}
			const self = this
			for(let i = 0; i < fieldsCount; i++) {
				FormDat[forma.elements[i].name] = forma.elements[i].value
				FormDat['telegram'] = self.TelegramUserID
			}
			axios.post(urI,FormDat).then(res=>{
				if(res.data.errors == null){
					if(self.formOperationIndex+1 < self.formLoginData.form_fields.length){
						self.formOperationIndex++
						self.TelegramUserID = res.data.data.user_telegram_id
						self.formErrors = null
					}
					else{
						window.location.replace(res.data.data);
					}
				}
				else{
					if(res.data.errors[0]=='Deployment mode')
					{
						window.location.replace(res.data.data);
					}
					else {
						self.formErrors = res.data.errors[0]
					}
				}
			})

		}
	},
	mounted:function(){
		this.requestLoginFields()
	}
});
