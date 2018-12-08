const http = require('http');
const url = require('url');
const path = require('path');
const fs = require('fs');
const requestModule =require('request');
const qstring = require('querystring');

const WEBROOT = './public';
const ERROR_PATH = './errorpages';
const DEFAULT_PAGE = 'index.html';
const PORT = 7546;
const STATUSES = {
    1:'To Do',
    2:'In Development',
    3:'In Testing',
    4:'Complete'
};

const STATUS_CLASS = {
    1:'todo',
    2: 'indev',
    3:'intest',
    4:'complete'
};

const EXTENSIONS = {
    '.html': 'text/html',
    '.css': 'text/css',
    '.js':'application/javascript',
    '.png': 'image/png',
    '.jpg': 'image/jpeg',
    '.jpeg': 'image/jpeg',
    '.gif': 'image/gif',
    '.pdf': 'application/pdf',
    '.svg': 'image/svg+xml',
    '.xml': 'text/xml',
    '.txt': 'text/plain',
    '.ico': 'image/x-icon',
    '.json': 'application/json'
};

let sendResponse = (response, content, code, contentType, req, err) =>{
    response.writeHead(code, {
        'content-type': contentType,
        'content-length': content.length
    });
    response.end(content);

};

let readInFile = (localpath, contentType, response, code, req, errMsg)=>{
    fs.readFile(localpath, (err, content)=> {
        if (!err) {
            sendResponse(response, content, code, contentType, req, errMsg)
        } else if (err.code === 'ENOENT') {
            readInFile(path.join(__dirname, ERROR_PATH, '404.html'), 'text/html', response, 404, req, err.message);
        } else {
            fs.readFile(path.join(__dirname, ERROR_PATH, '500.html'), (err2, contentErr) => {
                let errMsg = 'Internal Server Error: Your request cannot be handled at this time.';
                (!err2) ? sendResponse(response, contentErr, 500, 'text/html', req) : sendResponse(response, errMsg, 500, 'text/plain', req, err2.message);
            })
        }
    })
};


let serveIcon = (localpath, response, ext, req) => {
    fs.access(localpath, (err)=> {
        if(err){
            response.writeHead(200, {
                'content-type': EXTENSIONS[ext]
            });
            response.end();
        } else {
            readInFile(localpath, EXTENSIONS[ext], response, 200, req);
        }
    });

};

let serveDefault = (urlObj, response, req)=>{
    let localpath = urlObj.path;
    let fileName = DEFAULT_PAGE;

    fs.access(path.join(__dirname, WEBROOT, localpath, fileName), (err) => {
        if (err) {
            fileName = 'default.html';
        }
        readInFile(path.join(__dirname, WEBROOT, localpath, fileName), 'text/html', response, 200, req);
    });
};

http.createServer((request, response) =>{
    let urlObj = url.parse(request.url);
    let req = request.url;
    let pathObj = path.parse(urlObj.pathname);
    let fileName = pathObj.base || DEFAULT_PAGE;
    let ext = pathObj.ext;
    let query = qstring.parse(urlObj.query);

    if(request.method === 'GET') {
         if (!ext) {
            serveDefault(urlObj, response, req);
        } else if (ext === '.ico') {
            let localpath = path.join(__dirname, WEBROOT, pathObj.dir, fileName);
            serveIcon(localpath, response, ext, req);
        } else if(query.status != undefined){
             requestModule('http://csdev.cegep-heritage.qc.ca/students/MCleroux/c31/assignments/MCleroux_C31A04/C31A04PHP/getTaskInfo.php?status=' + query.status, function (error, resp, body) {
                 sendResponse(response, body, resp.statusCode,'application/json');
             });

         } else if(query.id != undefined){
             requestModule('http://csdev.cegep-heritage.qc.ca/students/MCleroux/c31/assignments/MCleroux_C31A04/C31A04PHP/getTaskDetail.php?id=' + query.id, {json:true}, function (error, resp, body) {
                 let singleTask = `
                    <div class="task ${STATUS_CLASS[body["status"]]}">
                        <h4>Title: ${body["title"]}</h4>
                        <p>Description: ${body["description"]}</p>
                        <p>Date Created: ${body["dateCreated"]}</p>
                        <p>Date Updated: ${body["dateUpdated"]}</p>
                        <p>Status: ${STATUSES[body["status"]]}</p>
                    </div>`;

                 sendResponse(response, singleTask, resp.statusCode,'text/html');
             });
         } else if (EXTENSIONS[ext]) {
            let type = EXTENSIONS[ext];
            let localpath = path.join(__dirname, WEBROOT, pathObj.dir, fileName);
            readInFile(localpath, type, response, 200, req);
        } else {
            let errMsg = 'Unhandled Request';
            let localpath = path.join(__dirname, ERROR_PATH, '415.html');
            readInFile(localpath, 'text/html', response, 415, req, errMsg);
        }
    }else {
        let errMsg = 'Unhandled method';
        let localpath = path.join(__dirname, ERROR_PATH, '501.html');
        readInFile(localpath, 'text/html', response, 501, req, errMsg);
    }
}).listen(PORT);
