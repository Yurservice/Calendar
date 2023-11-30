document.addEventListener("DOMContentLoaded", function() {
    window.current_filter = 0;
    fetch('api/').then(response => {
        if (!response.ok) {
        throw new Error(`Error: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if(data.length > 0) { 
            for (let i = 0; i < data.length; i++) {
                let elem = document.getElementById("" + data[i].day + "");
                let marker_html = getMarkerHtml(data[i].eventtype_id);
                elem.insertAdjacentHTML('beforeend', marker_html);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });

    let days = document.querySelectorAll(".current_month");
    for (let i = 0; i < days.length; i++) {
        days[i].addEventListener("click", function() {
            const dateString = days[i].parentElement.id;
            let short_date = getDateShortname(dateString);
            document.getElementById("modal_month").textContent = short_date;
            document.getElementById("new_event_date").value = dateString;

            if(event.target.nextElementSibling == null) document.getElementById("myModal").style.display = "flex";
            else { 
                fetch('api/all_in_day?day=' + dateString + '').then(response => {
                    if (!response.ok) {
                    throw new Error(`Error: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if(data.length > 0) { 
                        document.getElementById('events_wrap').innerHTML = '';
                        for (let i = 0; i < data.length; i++) {
                            let short_date = getDateShortname(data[i].day);
                            let info_date_class;
                            if(data[i].title == 'Meeting with an Expert') info_date_class = 'pink';
                            else if(data[i].title == 'Question-Answer') info_date_class = 'green';
                            else if(data[i].title == 'Conference') info_date_class = 'yellow';
                            else if(data[i].title == 'Webinare') info_date_class = 'blue';
                            let event_info_html = '<div class="event_conteiner"><div class="info_name_wrap"><p class="info_name">' + data[i].name + '</p><p onclick="edit_event(' + data[i].id + ')" class="pencil">&#128393;</p></div><p class="info_description">' + data[i].description + '</p><p class="info_location">' + data[i].location + '<p><div class="info_date_wrap ' + info_date_class + '"><p>' + short_date + ' ' + data[i].time + '</p><div>' + data[i].title + '</div></div></div>';
                            document.getElementById('events_wrap').insertAdjacentHTML('beforeend', event_info_html);
                            document.body.addEventListener('click', onClickOutsideFactory("info_popup"));
                        }
                        document.getElementById('add_event').addEventListener('click', function() {
                            document.getElementById("info_popup").style.display = "none";
                            document.getElementById("myModal").style.display = "flex";
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
                document.getElementById("info_popup").style.display = "block";
                let elementPosition = event.target.getBoundingClientRect();
                let windowWidth = window.innerWidth;
                var elementCenterX = elementPosition.left + elementPosition.width / 2;

                if (elementCenterX < windowWidth / 2) var left = elementPosition.left + 200;
                else var left = elementPosition.left - 300;
                document.getElementById("info_popup").style.top = elementPosition.top + 'px';
                document.getElementById("info_popup").style.left = left + 'px';
            }
        });
    }
    
    var closeButtons = document.getElementsByClassName('close');
    for (var i = 0; i < closeButtons.length; i++) {
        closeButtons[i].addEventListener('click', closeModal);
    }
    document.getElementById("cansel_button").addEventListener('click', closeModal);
    document.getElementById("add_button").addEventListener('click', sendForm);
});

function filter(event, type) {
    if(type == window.current_filter) {
        let elements = document.querySelectorAll('.marker');
        elements.forEach(function(element) {
            element.style.display = 'inline-block';
        });
        window.current_filter = 0;
        event.target.classList.remove('active');
    }
    else {
        let elements = document.querySelectorAll('.marker');
        elements.forEach(function(element) {
            element.style.display = 'none';
        });
        let classname;
        if(type == '1') classname = '.marker.meeting';
        else if(type == '2') classname = '.marker.questions';
        else if(type == '3') classname = '.marker.conference';
        else if(type == '4') classname = '.marker.webinar';

        elements = document.querySelectorAll(classname);
        elements.forEach(function(element) {
            element.style.display = 'inline-block';
        });
        window.current_filter = type;
        let filters = document.querySelectorAll('.filter_item');
        filters.forEach(function(filter) {
            filter.classList.remove('active');
        });
        event.target.classList.add('active');
    }
}

function onClickOutsideFactory(panel_id) {
    return function onClickOutside(event) {
    let panel = document.getElementById(panel_id);
    if (panel && !panel.contains(event.target)) {
        if(event.target.classList[0] == 'current_month'&&event.target.nextElementSibling !== null) {}
        else {
            panel.style.display = "none";
            document.body.removeEventListener('click', onClickOutside);
        }
    }
    }
}

function edit_event(id) {
    fetch('api/show?id=' + id + '').then(response => {
        if (!response.ok) {
        throw new Error(`Error: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        document.getElementById("info_popup").style.display = "none";
        document.getElementById("myModal").style.display = "flex";
        document.getElementById('new_event_name').value = data.name;
        document.getElementById('new_event_description').value = data.description;
        document.getElementById('new_event_location').value = data.location;
        document.getElementById('new_event_date').value = data.day;
        document.getElementById('event_id').value = data.id;

        let options = document.getElementById('new_event_time').options;
        for (let i = 0; i < options.length; i++) {
            if (options[i].text === data.time) {
                options[i].selected = true;
                break;
            }
        }
        
        options = document.getElementById('new_event_type').options;
        for (let i = 0; i < options.length; i++) {
            if (options[i].value == data.eventtype_id) {
                options[i].selected = true;
                break;
            }
        }

        document.getElementById('modal_title').textContent = "Edit event";
        let add_button = document.getElementById('add_button');
        add_button.textContent = "Save";
        let delete_button = '<button id="delete_button">Delete</button>';
        add_button.insertAdjacentHTML('beforebegin', delete_button);
        document.getElementById('add_button').id = 'save_button';
        document.getElementById("save_button").addEventListener('click', sendForm);
        document.getElementById("delete_button").addEventListener('click', sendForm);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function getDateShortname(day) {
    let dateParts = day.split('-');
    let date = new Date(`${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`);
    let s_day = date.getDate();
    let monthName = date.toLocaleString('en-US', { month: 'long' }).toLowerCase();
    let modal_date = s_day + " " + monthName + " at ";
    return modal_date;
}

function closeModal() {
    document.getElementById('validation_err').textContent = '';
    document.getElementById('myModal').style.display = 'none';
    document.getElementById('info_popup').style.display = 'none';
    clearForm();
}

function getMarkerHtml(type) {
    let marker_html;
    if(type == '1') marker_html = '<span class="marker meeting"></span>';
    else if(type == '2') marker_html = '<span class="marker questions"></span>';
    else if(type == '3') marker_html = '<span class="marker conference"></span>';
    else if(type == '4') marker_html = '<span class="marker webinar"></span>';
    return marker_html;
}

function sendForm(event) {
    let url;
    let crud_type;
    if(event.target.id == "add_button") { url = "api/save_event"; crud_type = 'create'; }
    else if(event.target.id == "save_button") { url = "api/update"; crud_type = 'update';}
    else if(event.target.id == "delete_button") { url = "api/destroy"; crud_type = 'destroy';}

    document.getElementById('validation_err').textContent = '';
    let new_event_name = document.getElementById('new_event_name').value;
    let new_event_description = document.getElementById('new_event_description').value;
    let new_event_location = document.getElementById('new_event_location').value;
    
    let event_time_index = document.getElementById('new_event_time').selectedIndex;
    let new_event_time = document.getElementById('new_event_time').options[event_time_index].text;
    
    let event_type_index = document.getElementById('new_event_type').selectedIndex;
    let new_event_type = document.getElementById('new_event_type').options[event_type_index].value;
    
    let new_event_date = document.getElementById('new_event_date').value;
    let event_id = document.getElementById('event_id').value;

    if(new_event_name == '') document.getElementById('validation_err').textContent = 'Event name is required';
    if(new_event_description == '') document.getElementById('validation_err').textContent = 'Event description is required';
    if(new_event_location == '') document.getElementById('validation_err').textContent = 'Event location is required';
    
    if(document.getElementById('validation_err').textContent == '') {
        fetch(url, {
            method: "POST", headers: {"Content-Type": "application/json",},
            body: JSON.stringify({ name:new_event_name,description:new_event_description,location:new_event_location,time:new_event_time,type:new_event_type,date:new_event_date,event_id:event_id }),
        }).then(response => response.json())
        .then(response => { 
            if(crud_type == 'create') {
                if(response == true) {
                    let added_event_day = document.getElementById(new_event_date);
                    let marker_html = getMarkerHtml(new_event_type);
                    added_event_day.insertAdjacentHTML('beforeend', marker_html);
                }
                else alert('An unknown error has occurred');
            }
            if(crud_type == 'update') {
                if(response === false) alert('An unknown error has occurred');
            }
            if(crud_type == 'destroy') {
                if(response === 0) {
                    let added_event_day = document.getElementById(new_event_date);
                    let classname;
                    if(new_event_type == '1') classname = '.marker.meeting';
                    else if(new_event_type == '2') classname = '.marker.questions';
                    else if(new_event_type == '3') classname = '.marker.conference';
                    else if(new_event_type == '4') classname = '.marker.webinar';

                    added_event_day.querySelector(classname).remove();
                }
                else if(response === false) alert('An unknown error has occurred');
            }
        }).catch(error => console.log(error));
        closeModal();
    }
}

function clearForm() {
    let inputElements = document.querySelectorAll('input, textarea');
    inputElements.forEach(function(element) {
        element.value = '';
    });
    let selectElements = document.querySelectorAll('select');
    selectElements.forEach(function(element) {
        var options = element.options;
        for (var j = 0; j < options.length; j++) {
            options[j].selected = false;
        }
    });
    document.getElementById('modal_title').textContent = "Add event";
    let delete_button = document.getElementById('delete_button');
    if(delete_button) delete_button.remove();
    let save_button = document.getElementById('save_button');
    if(save_button) {
        save_button.textContent = "Add";
        save_button.id = 'add_button';
    }
}
