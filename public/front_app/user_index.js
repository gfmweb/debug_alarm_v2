addEventListener("submit", function(event) {
	event.preventDefault();
}, true);
const Connect = new Vue({
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

});

