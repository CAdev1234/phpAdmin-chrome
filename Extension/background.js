const serverUrl = 'http://localhost:5000'
function xhrListener() {
    return this.responseText
} 

function sleep(ms) {
    return new Promise((resolve) => {
        setTimeout(resolve, ms)
    })
}

function create_UUID(){
    var dt = new Date().getTime();
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (dt + Math.random()*16)%16 | 0;
        dt = Math.floor(dt/16);
        return (c=='x' ? r :(r&0x3|0x8)).toString(16);
    });
    return uuid;
}

function getIpAddress() {
    return fetch('http://ipinfo.io/?format=jsonp&callback=getIP', {
        'headers': {
            "accept": "application/json, text/javascript, */*; q=0.01",
            "accept-language": "en-US,en;q=0.9",
            "content-type": "application/json",
            "sec-ch-ua": "\"Chromium\";v=\"88\", \"Google Chrome\";v=\"88\", \";Not A Brand\";v=\"99\"",
            "sec-ch-ua-mobile": "?0",
            "sec-fetch-dest": "empty",
            "sec-fetch-mode": "cors",
            "sec-fetch-site": "same-origin",
            "x-requested-with": "XMLHttpRequest",
        },
        'method': 'GET',
    })
}

function convertTZ(date, tzString) {
    return new Date((typeof date === "string" ? new Date(date) : date).toLocaleString("en-US", {timeZone: tzString}));   
}

function calcDateDiff(start_date, end_date) {
    console.log(start_date, end_date)
    var sec = Math.floor((end_date - start_date) / 1000)
    var min = Math.floor(sec / 60)
    var hour = Math.floor(sec / 3600)
    return `${String(hour)} hours and ${String(min)} minutes, ${String(sec % 60)} seconds`
}

function onLineStatusReq(user_identity) {
    var formData = new FormData()
    formData.append('user_identity', user_identity)
    return fetch(`${serverUrl}/api/detect_last_activity.php`, {
        'headers': {
            "accept": "application/json, text/javascript, */*; q=0.01",
            "accept-language": "en-US,en;q=0.9",
            "sec-ch-ua": "\"Chromium\";v=\"88\", \"Google Chrome\";v=\"88\", \";Not A Brand\";v=\"99\"",
            "sec-ch-ua-mobile": "?0",
            "sec-fetch-dest": "empty",
            "sec-fetch-mode": "cors",
            "sec-fetch-site": "same-origin",
            "x-requested-with": "XMLHttpRequest",
        },
        method      : 'POST',
        body        : formData
    })
}

function postToServer(formData) {
    return fetch(`${serverUrl}/api/server.php`, {
        'headers': {
            "accept": "application/json, text/javascript, */*; q=0.01",
            "accept-language": "en-US,en;q=0.9",
            "sec-ch-ua": "\"Chromium\";v=\"88\", \"Google Chrome\";v=\"88\", \";Not A Brand\";v=\"99\"",
            "sec-ch-ua-mobile": "?0",
            "sec-fetch-dest": "empty",
            "sec-fetch-mode": "cors",
            "sec-fetch-site": "same-origin",
            "x-requested-with": "XMLHttpRequest",
        },
        'method': 'POST',
        body: formData,
    })
}

function uploadScreenShot(dataURI) {
    var formData = new FormData()
    formData.append('screenshot', dataURI)
    return fetch(`${serverUrl}/api/upload.php`, {
        'headers': {
            "accept": "application/json, text/javascript, */*; q=0.01",
            "accept-language": "en-US,en;q=0.9",
            "sec-ch-ua": "\"Chromium\";v=\"88\", \"Google Chrome\";v=\"88\", \";Not A Brand\";v=\"99\"",
            "sec-ch-ua-mobile": "?0",
            "sec-fetch-dest": "empty",
            "sec-fetch-mode": "cors",
            "sec-fetch-site": "same-origin",
            "x-requested-with": "XMLHttpRequest",
        },
        'method': 'POST',
        body: formData,
    })
}


chrome.tabs.onActivated.addListener(function(activeInfo) {
    console.log(activeInfo)
    setTimeout(() => {
        captureSite(activeInfo)
    }, 500);
});

  
function captureSite(activeInfo) {
    chrome.tabs.query({currentWindow: true, active: true}, (tabs) => {
        console.log(tabs)
        if(tabs[0].url.includes('chrome://extensions')) {
            return
        }else {
            chrome.tabs.captureVisibleTab(null, {format: 'png'}, async function(dataURI) {
                if (dataURI) {
                    var res_data = await uploadScreenShot(dataURI).then(res => res.json())
                    await chrome.storage.local.get(['tab_active_time', 'user_identity'], async function(result) {
                        var tab_active_time                 = result.tab_active_time
                        var tab_deactive_time               = new Date().getTime()
                        var user_identity                   = result.user_identity
        
                        await chrome.storage.local.set({tab_active_time: tab_deactive_time})
                        await chrome.tabs.executeScript(activeInfo.tabId, {
                            file: 'contentScript.js'
                        }, async function(result) {
                            const lastErr                   = chrome.runtime.lastError
                            if (lastErr) console.log('tab: ' + activeInfo.tabId + ' lastError: ' + JSON.stringify(lastErr))
                            else {
                                // Get IpAddress and Site Url
                                var ipaddress_res           = await getIpAddress().then(res => res.text())
                                ipaddress_res               = ipaddress_res.slice(ipaddress_res.indexOf('{'), ipaddress_res.indexOf('}') + 1)
                                ipaddress_res               = JSON.parse(ipaddress_res)
                                var postData                = Object.assign(ipaddress_res, result[0])
                                postData.screenshot         = res_data.data.img_path
                                postData.time_spending      = calcDateDiff(tab_active_time, tab_deactive_time)
                                postData.last_connect       = new Date(tab_deactive_time).toLocaleString()
                                // postData.last_connect       = convertTZ(new Date(tab_deactive_time), ipaddress_res.timezone)
                                postData.user_identity      = user_identity
                                var formData                = new FormData()
                                for (let index = 0; index < Object.keys(postData).length; index++) {
                                    formData.append(Object.keys(postData)[index], postData[Object.keys(postData)[index]])
                                }
                                var postRes = await postToServer(formData).then(res => res.text())
                            }
                        })
                    })
                    
                }
            })
        }
    })
    
}

chrome.browserAction.onClicked.addListener(function(tab) {
    
})

chrome.runtime.onInstalled.addListener(async function() {
    var first_active_time = new Date().getTime()
    chrome.storage.local.set({tab_active_time: first_active_time});
    var ipaddress_res           = await getIpAddress().then(res => res.text())
    ipaddress_res               = ipaddress_res.slice(ipaddress_res.indexOf('{'), ipaddress_res.indexOf('}') + 1)
    ipaddress_res               = JSON.parse(ipaddress_res)
    await chrome.storage.local.set({user_identity: ipaddress_res.ip})
    setInterval(async () => {
        chrome.storage.local.get(['user_identity'], async function(result) {
            var online_res = await onLineStatusReq(result.user_identity).then(res => res.text()) 
        })
    }, 3000);
});