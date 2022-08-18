Vue.component('logs_table', {
	props: ['ActualListLogs','Filters'],
	methods: {
		InitAction(log_id){

		 return	this.$root.$emit('get_info',log_id)
		}
	},
	template: '<table class="table table-striped table-hover table-bordered table info mt-5">\n' +
		'\t\t\t\t\t<thead class="text-center">\n' +
		'\t\t\t\t\t\t<th>Сервис</th>\n' +
		'\t\t\t\t\t\t<th>Title</th>\n' +
		'\t\t\t\t\t\t<th>Время</th>\n' +
		'\t\t\t\t\t</thead>\n' +
		'\t\t\t\t\t<tbody>\n' +
		'\t\t\t\t\t\t<tr v-for="(log, index) in ActualListLogs" role="button"\n' +
		'\t\t\t\t\t\t    v-on:click="InitAction(log.log_id)"\n' +
		'\t\t\t\t\t\t\tv-if="\n' +
		'\t\t\t\t\t\t\t(log.project_name == Filters.project_name || Filters.project_name==\'null\')\n' +
		'\t\t\t\t\t\t\t&&(log.title==Filters.title || Filters.title == \'\')\n' +
		'\t\t\t\t\t\t\t&&(log.part == Filters.part || Filters.part == \'null\')\n' +
		'\t\t\t\t\t\t\t&&(log.status == Filters.status || Filters.status == \'null\')\n' +
		'\t\t\t\t\t\t\t\t "\n' +
		'\t\t\t\t\t\t>\n' +
		'\t\t\t\t\t\t\t<td v-text="log.project_name" class="bg-danger" v-if="log.status==\'critical\'"></td>\n' +
		'\t\t\t\t\t\t\t<td v-text="log.project_name" class="bg-info" v-if="log.part==\'start\' && log.status!==\'critical\'"></td>\n' +
		'\t\t\t\t\t\t\t<td v-text="log.project_name" class="bg-primary" v-if="log.part==\'finish\' && log.status!==\'critical\'"></td>\n' +
		'\t\t\t\t\t\t\t<td v-text="log.project_name" v-if="log.part==\'body\' && log.status!==\'critical\'"></td>\n' +
		'\t\t\t\t\t\t\t<td v-text="log.title" class="bg-danger" v-if="log.status==\'critical\'" ></td>\n' +
		'\t\t\t\t\t\t\t<td v-text="log.title" class="bg-info" v-if="log.part==\'start\' && log.status!==\'critical\'" ></td>\n' +
		'\t\t\t\t\t\t\t<td v-text="log.title" class="bg-primary" v-if="log.part==\'finish\' && log.status!==\'critical\'" ></td>\n' +
		'\t\t\t\t\t\t\t<td v-text="log.title" v-if="log.part==\'body\' && log.status!==\'critical\'" ></td>\n' +
		'\t\t\t\t\t\t\t<td v-text="log.time" class="bg-danger" v-if="log.status==\'critical\'"></td>\n' +
		'\t\t\t\t\t\t\t<td v-text="log.time" class="bg-info" v-if="log.part==\'start\' && log.status!==\'critical\'"></td>\n' +
		'\t\t\t\t\t\t\t<td v-text="log.time" class="bg-primary" v-if="log.part==\'finish\' && log.status!==\'critical\'"></td>\n' +
		'\t\t\t\t\t\t\t<td v-text="log.time" v-if="log.part==\'body\' && log.status!==\'critical\'"></td>\n' +
		'\t\t\t\t\t\t</tr>\n' +
		'\t\t\t\t\t</tbody>\n' +
		'\t\t\t\t</table>'
});
