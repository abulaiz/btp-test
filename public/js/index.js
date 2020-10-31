class App {
	constructor(){
	    moment.locale('id')
		this.months = moment.months()
		this.methods = []

		this.period = $("#period")
		this.year = $("#year")
	}

	async getMethodList(){
		await axios.get('/api/methods').then(response => {
			this.methods = response.data
		})		
	}

	setMonths(){
	    let month_headers = $("th.month")
	    let inc = $("#period").val() == 1 ? 0 : 6;

	    for(let i = 0; i < month_headers.length; i++){
	        month_headers[i].innerHTML = this.months[i+inc]
	    }
	}

	generateYears(){
		let current_year = parseInt(new Date().getFullYear());
		let years = [];
		// range of year
		let range_year = 4; 

		for(let i = range_year/2; i >= 1; i--)
			years.push(current_year-i)
		
		years.push(current_year)

		for(let i = 1; i <= range_year/2; i++)
			years.push(current_year-i)	

		// set into options
		for(let i in years)
			this.year.append(`<option value="${years[i]}">${years[i]}</option>`) 

		this.year.val(current_year)
	}

	async setMethods(){
		await this.getMethodList()

		// Prepare methods row on table
		let container = $("#activity-list-container")
		let raw_element = container.html();
		for(let i = 0; i < this.methods.length - 1; i++){
			container.append(raw_element)
		}

		// Fill method column with column name
	    let method_headers = $("th.methods")
	    for(let i = 0; i < this.methods.length; i++){
	        method_headers[i].innerHTML = this.methods[i].name
	    }		
	}

	async init(){
		this.generateYears()
		await this.setMonths()
		await this.setMethods()
	}
}

class ActivityDialog {
	constructor(){
		this.dialog = $("#activity-modal")

		this.id = null
		this.name = this.dialog.find("[name=name]")
		this.method = this.dialog.find("[name=method_id]")

		this.started_date = null;
		this.ended_date = null;

		this.daterangepicker = this.dialog.find("[name=daterange]")

		this.daterangepicker.daterangepicker({
			locale: {
      			format: 'DD/MM/YYYY'
    		}
		})

		this.operation_type = null; // 1 : add data, 2 : edit data, null : unsetled		
	}

	setMethodOptions(data, params){
		for(let i in data){
			this.method.append(`
				<option value="${data[i][params['value']]}">${data[i][params['text']]}</option>
			`)
		}
	}

	openDialog(){
		this.dialog.modal({show : true, backdrop: true, keyboard: false})
	}

	setTitle(title){
		this.dialog.find("h5").text(title)
	}

	addData(){
		this.openDialog()
		this.setTitle("Tambah Activity")
		this.operation_type = 1
		$("#remove").hide()
	}

	editData(data){
		this.id = data.id
		this.name.val(data.name)
		this.method.val(data.method_id)

		let started_date = data.started_date.split('-').reverse().join('/')
		let ended_date = data.ended_date.split('-').reverse().join('/')

		this.daterangepicker.data('daterangepicker').setStartDate(started_date);
		this.daterangepicker.data('daterangepicker').setEndDate(ended_date);

		this.setTitle("Data Activity")
		this.operation_type = 2
		this.openDialog()		
		$("#remove").show()
	}

	async storeData(onsuccess){
		let dates = this.daterangepicker.val().split(" - ")

		await axios.post('/api/activities', {
			name : this.name.val(),
			method_id : this.method.val(),
			started_date : dates[0].split('/').reverse().join('-'),
			ended_date : dates[1].split('/').reverse().join('-')
		}).then(response => {
			if(response.data.success){
				this.dialog.modal("toggle")
				onsuccess()
			}
		})
	}

	async updateData(onsuccess){
		let dates = this.daterangepicker.val().split(" - ")

		await axios.put('/api/activities/'+this.id, {
			name : this.name.val(),
			method_id : this.method.val(),
			started_date : dates[0].split('/').reverse().join('-'),
			ended_date : dates[1].split('/').reverse().join('-')
		}).then(response => {
			if(response.data.success){
				this.dialog.modal("toggle")
				onsuccess()
			}
		})
	}

	async submit(onsuccess){
		if(this.operation_type == 1)
			await this.storeData(onsuccess)
		else
			await this.updateData(onsuccess)
	}
}

class ActivityList {
	constructor(){
		this.event_template = `
            <div class="event" data-id="$id" data-month="$month" data-method="$method" onclick="eventClicked(this)">
              <p class="name">$name</p>
              <p class="date">$date</p>
            </div>
		`
		this.data = {}
	}

	findDataById(id, month, method){
		let data = this.data['method-'+method]['month-'+month]
		for(let i in data)
			if(data[i].id == id)
				return data[i]
		return null
	}

	async loadList(year, period){
		$(".event").remove()

		await axios.get(`/api/activities?year=${year}&period=${period}`)
		.then(response => {
			this.data = response.data
			console.log(this.data)
		})

		let methods_container = $("#activity-list-container tr");
		let month_inc = parseInt(period) == 1 ? 1 : 7;

		for(let i = 0; i < methods_container.length; i++){
			let month_container = $(methods_container[i]).find("td.month")
			for(let j = 0; j < month_container.length; j++){
				let data = []
				try{
					data = this.data[`method-${i+1}`][`month-${j+month_inc}`]
				} catch {}

				for(let x in data){
					// copy string element
					let element = this.event_template.replace("$id", data[x].id)
					element = element.replace("$month", j+1)
					element = element.replace("$method", i+1)
					element = element.replace("$name", data[x].name)
					
					element = element.replace("$date", (() => {
						let started_date = data[x].started_date.split('-').reverse().join('/')
						let ended_date = data[x].ended_date.split('-').reverse().join('/')

						return `(${started_date} - ${ended_date})`
					})())

					$(month_container[j]).append(element)		
				}
			}
		}
	}

	async remove(id, onsuccess){
		await axios.delete('/api/activities/'+id).then(response => {
			if(response.data.success){
				onsuccess()
			}
		})
	}	
}

const app = new App()
const dialog = new ActivityDialog() 
const list = new ActivityList()

function eventClicked(e){
	let id = $(e).data('id')

	let month = $(e).data('month')
	if(parseInt(app.period.val()) == 2)
		month += 6

	let method = $(e).data('method')
	
	let data = list.findDataById(id, month, method)
	dialog.editData(data)
}

$(document).ready(async function(){
	await app.init()
	await dialog.setMethodOptions(app.methods, {value : 'id', text : 'name'})
	list.loadList(app.year.val(), app.period.val())
})

$("#period").change(function(){
	app.setMonths()
	list.loadList(app.year.val(), app.period.val())
})

$("#year").change(function(){
	list.loadList(app.year.val(), app.period.val())
})

$("#add-button").click(function(){
	dialog.addData()
})

$("#submit").click(async function(){
	$(this).attr({"disabled" : "disabled"})
	
	await dialog.submit(() => {
		// onsuccess
		list.loadList(app.year.val(), app.period.val())
	})

	$(this).removeAttr("disabled")
})

$("#remove").click(async function(){
	swal({
		title: "Apakah anda yakin ?",
		text: "Data activity terpilih akan dihapus",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then(async (willDelete) => {
		if (willDelete) {
			await list.remove(dialog.id, () => {
				// onsuccess
				list.loadList(app.year.val(), app.period.val())
				dialog.dialog.modal("toggle")
			})
		}
	});
})