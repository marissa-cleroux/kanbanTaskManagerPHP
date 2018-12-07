addEventListener('load', ()=>{

    const STATUS_CLASS = {
        1:'todo',
        2: 'indev',
        3:'intest',
        4:'complete'
    };

    const STATUS_OUTPUT = {
        1:'To do',
        2:'In Development',
        3:'In test',
        4:'Complete'
    };

    let dropdown = document.getElementById('status');
    let section = document.getElementById('tasks');
    let interval;

    dropdown.addEventListener('change', (e)=>{
        clearInterval(interval);
        console.log('event fired');
        getRequest(e.target.value);
        interval = setInterval(()=>{getRequest(e)}, 30000);
    });

    let getRequest = (selected) => {
        section.innerHTML = "";
        fetch(`?status=${selected}`, {
            Method: 'get'
        }).then((response) => {
            console.log('first response');
            return response.text();
        }).then((text) =>{
            console.log('second response');
            let tasks = JSON.parse(text);
            console.log(tasks);
            displayTasks(tasks);
        }).catch( error =>{
            console.log('Request failed: ', error);
        })
    };

    let displayTasks = (tasks) => {
        section.innerHTMl = "";
        Array.from(tasks).map((task) => {
            outputTask(task.title, task.id, task.status, task.dateUpdated);
        });
    };

    let outputTask = (title, id, status, dateUpdated) =>{
        let task = `<div id="${id}" class="task ${STATUS_CLASS[status]}">
                        <h3>Title: ${title}</h3>                    
                        <p>Status: ${STATUS_OUTPUT[status]}</p>
                        <p>Date last updated: ${dateUpdated}</p>`;

        section.innerHTML += task;
    };


    getRequest(dropdown.value);
    interval = setInterval(() => {getRequest(dropdown.value)}, 30000);
});