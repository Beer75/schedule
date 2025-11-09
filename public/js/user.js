// async function getData(fetch_file, params){
//     // console.log(params);
//     let fetch_data=await fetch(fetch_file, {
//         method: 'post',
//         headers: {
//           "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
//           'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//         },
//         body: JSON.stringify(params)
//       });
//     let json_data= await fetch_data.json();
//     // console.log(json_land);
//     return json_data;
// }

async function sendData(fetch_file, params){
    try{
        const response=await fetch(fetch_file, {
            method: 'POST',
            headers: {
                "Content-type": "application/json",
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(params)
        });
        if(!response.ok){
            throw new Error('network error');
        }
        const data=await response.json();
        return data;
    }
    catch(error){
        console.error(error);
    }
}

async function getData(fetch_file){
    try{
        const response=await fetch(fetch_file);
        const data=await response.json();
        //if(data.success){
            return data;
        //}
        //else{
        //    console.error('Error: ', data.message);
        //}
    }
    catch(error){
        console.error(error);
    }
}

function showModal(id){
    document.getElementById(id).style.visibility='visible';


}

function hideModal(id){
    document.getElementById(id).style.visibility='hidden';


}

function setSelect(SelId, Opt){
    let optSel = document.getElementById(SelId);
    for (let i = 0; i < optSel.length; i++) {
        if (optSel.options[i].value == Opt) {
            optSel.options[i].selected = true;
        }
    }
}

function isset(variable){
    return typeof variable!=='undefined';
}

function render(templateName, model){
    const templateElement=document.getElementById(templateName);
    if(templateElement === null){
        return null;
    }
    const templateSource=templateElement.innerHTML;
    const renderFn=Handlebars.compile(templateSource, "noEscape=true");
    const strHtml=renderFn(model);
    return strHtml;
}

function confirmSubmitForm(formId, msgText){
    let answer=confirm(msgText);
    if(answer){
        let submitForm=document.querySelector('#'+formId);
        if(submitForm){
            submitForm.submit();
        }
    }
}