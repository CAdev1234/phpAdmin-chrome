// chrome.runtime.onMessage.addListener(function(request, sender, sendResponse) {
//     console.log('12345=', request)
//     if (request.type === 'getting_site_url') {
//         console.log('1234=', request)
//         chrome.runtime.sendMessage({type: 'send_site_url', site_url: window.location.hostname})
//     }
//     sendResponse({
//         response: 'Message received'
//     })
//     return true
// })

(function() {
    var obj_var = {}
    obj_var.site_url = window.location.hostname
    return obj_var
})();