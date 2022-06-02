let source_href = window.location.href.split('?')[0];
let old_params = window.location.href.split('?').length > 1 ? window.location.href.split('?')[1] : '';

let filter_status_task = old_params.split('status=').length > 1 ? old_params.split('status=')[1].split('&')[0] : null;
let filter_day_task = old_params.split('day=').length > 1 ? old_params.split('day=')[1].split('&')[0] : null;
let filter_date_task = old_params.split('date=').length > 1 ? old_params.split('date=')[1].split('&')[0]: null;

let elements = document.getElementsByClassName("element_for_filter");
for (let index = 0; index < elements.length; index++)
{
    if (elements[index].tagName == "SELECT" || elements[index].tagName == "INPUT")
    {
        elements[index].addEventListener("change", filter_manage);
    }
    else
    {
        elements[index].addEventListener("click", filter_manage);
    }
}

function filter_manage(event)
{
    let params = "?";

    if (event.target.tagName == "SELECT")
    {
        filter_status_task = event.target.value;
    }
    else if (event.target.tagName == "INPUT")
    {
        filter_day_task = event.target.value;
        filter_date_task = null;
    }
    else if (event.target.tagName == "SPAN")
    {
        filter_date_task = event.target.attributes.value.value;
        filter_day_task = null;
    }

    if (filter_status_task != null) { params += "status=" + filter_status_task + "&"; }
    if (filter_day_task != null) {params += "day=" + filter_day_task + "&";}
    if (filter_date_task != null) {params += "date=" + filter_date_task;}

    window.location.href = source_href + params;

    return;
}

elements = document.getElementsByClassName("list_cont_tasks_td_taskName");
for (let index = 0; index < elements.length; index++)
{
    elements[index].addEventListener("click", task_editor_manage);
}

let previous = null;

function task_editor_manage(event)
{
    if (previous) { previous.style.color = ""; }   
    this.style.color = "red";

    document.getElementsByClassName("task_cont_header")[0].innerHTML = "Редактирование";    

    let row = this.parentNode;    
    let form = document.getElementsByClassName("form_cont_form")[0];   

    
		form[0].value = row.children[0].innerHTML;   

    for (let index = 0; index < form[1].children.length; index++)  
    {
        if (form[1].children[index].selected) { form[1].children[index].selected = false; }
        if (form[1].children[index].innerHTML.includes(row.children[1].innerHTML)) { form[1].children[index].selected = true; }
    }

    form[2].value = row.children[2].innerHTML;   

    let temp = row.children[3].innerHTML.split(" ");
		form[3].value = temp[0] + "T" + temp[1];  
  
    for (let index = 0; index < form[4].children.length; index++)   
    {
        if (form[4].children[index].selected) { form[4].children[index].selected = false; }
        if (form[4].children[index].innerHTML.includes(row.children[4].innerHTML)) { form[4].children[index].selected = true; }
    }

    form[5].value = row.children[5].innerHTML;   

    form[6].innerHTML = "Сохранить";   

    if (!form[7]) 
		{ 
			let mark_element = document.createElement('input'); 
			mark_element.type = "hidden"; 
			mark_element.name = "task_id"; 
			form.append(mark_element); 

			let cancel_button = document.createElement('button'); 
			cancel_button.innerHTML = "Отмена"; 
			cancel_button.type = "reset"; 
			cancel_button.classList += "form_cont_button"; 
			cancel_button.style = "margin-left: 1%;"; 
			form.append(cancel_button); 

			form[8].addEventListener("click", __exit); 
      let eelement = document.createElement('input'); 
			eelement.type = "hidden"; 
			eelement.name = "if_done";
			form.append(eelement);
		}

    
    let task_id = row.children[1].getAttribute("data__id");
    form[7].value = task_id;
    
    previous = this;
}

function __exit()
{
    window.location.href = window.location.href;
}