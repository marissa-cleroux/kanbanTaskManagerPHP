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
        getTasks(e.target.value);
        interval = setInterval(()=>{getTasks(e.target.value)}, 30000);
    });

    section.addEventListener('click', (e)=> {
        if(e.target.classList.contains('multitask')){
           clearInterval(interval);
           getSingleTask(e.target.id);
        } else if (e.target.parentNode.classList.contains('multitask')){
            clearInterval(interval);
            getSingleTask(e.target.parentNode.id);
        }
    });

    let getTasks = (selected) => {
        section.innerHTML = "";
        fetch(`?status=${selected}`, {
            Method: 'get'
        }).then((response) => {
            return response.text();
        }).then((text) =>{
            let tasks = JSON.parse(text);
            displayTasks(tasks);
        }).catch( error =>{
            console.log('Request failed: ', error);
        })
    };

    let getSingleTask = (selected) => {
        section.innerHTML = "";
        fetch(`?id=${selected}`, {
            Method: 'get'
        }).then((response) => {
            return response.text();
        }).then((text) =>{
            section.innerHTML += text;
        }).catch( error =>{
            console.log('Request failed: ', error);
        });

    };

    let displayTasks = (tasks) => {
        section.innerHTMl = "";
        Array.from(tasks).map((task) => {
            outputTask(task.title, task.id, task.status, task.dateUpdated);
        });
    };

    let outputTask = (title, id, status, dateUpdated) =>{

        let task = `<div id="${id}" class="task ${STATUS_CLASS[status]} multitask">
                        <h3>Title: ${title}</h3>                    
                        <p>Status: ${STATUS_OUTPUT[status]}</p>
                        <p>Date last updated: ${dateUpdated}</p>`;

        section.innerHTML += task;
    };


    getTasks(dropdown.value);
    interval = setInterval(() => {getTasks(dropdown.value)}, 30000);
});