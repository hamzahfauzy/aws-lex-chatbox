var user_id = ''
var base_url = window.location.origin;

async function initChat()
{
    var request  = await fetch(base_url + '/api/chatbot')
    var response = await request.json()

    user_id = response.data.id
    appendBubble('agent','HamzahBot',response.data.message)
}

// async function initUserId

async function sendMessage()
{
    var message = document.querySelector('#message').value
    appendBubble('admin','User',message)

    if(user_id == '')
    {
        return initChat()
    }

    var formData = new FormData;
    formData.append('user_id', user_id)
    formData.append('message', message)
    var request  = await fetch(base_url + '/api/chatbot/send',{
        method:'POST',
        body:formData
    })

    var response = await request.json()
    appendBubble('agent','HamzahBot',response.data.message)

    if(response.data.dialogState == 'Fulfilled')
    {
        user_id = ''
    }
}

function appendBubble(from, name, message)
{
    var chatElement = document.querySelector('ul.chat')
    chatElement.innerHTML += `
    <li class="${from} clearfix">
        <span class="chat-img ${from=='agent'?'left':'right'} clearfix mx-2">
            <img src="https://upload.wikimedia.org/wikipedia/commons/8/89/Portrait_Placeholder.png" alt="Agent" class="img-circle" />
        </span>
        <div class="chat-body clearfix">
            <div class="header clearfix">
                <strong class="primary-font">${name}</strong> <small class="right text-muted">
            </div>
            <p>
                ${message}
            </p>
        </div>
    </li>
    `

    var chatContainer = document.querySelector('.chat-care')
    chatContainer.scrollTo(0, chatContainer.scrollHeight)

    document.querySelector('#message').value = ''
    document.querySelector('#message').focus()
}

initChat()