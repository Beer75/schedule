let arrLessons;
let arrEmployers;
let arrGroups;
let arrPlan;

async function start(fetch_url){
    let sp=await getData(fetch_url);
    arrPlan=sp.plan;
    arrLessons=sp.lessons;
    arrEmployers=sp.employers;
    arrGroups=sp.groups;
    document.querySelector('#study_plan').addEventListener('click', clickStudyplan);
}


function clickStudyplan(event){
    if(event.target.classList.contains('clicker')){
        let quantity=event.target.dataset.q;
        let lid=event.target.dataset.lid;
        let gid=event.target.dataset.gid;
        let group=arrGroups.find(item=>item.id==gid);
        let gname=group.num+group.ind+' - '+group.name;
        let lname=arrLessons.find(item=>item.id==lid).name;
        // let eid=event.target.dataset.eid;
        let pid=event.target.dataset.pid;

        if(isset(pid)){ // Edit
            setSelect("edit_sp_employer_id", event.target.dataset.eid)
            document.querySelector("#edit_sp_q").value=quantity;
            document.querySelector("#edit_sp_pid").value=pid;
            document.querySelector("#edit_sp_lname_id").innerHTML=lname;
            document.querySelector("#edit_sp_gname_id").innerHTML=gname;
            document.getElementById("editStudyplanModalForm").querySelector('.errors').style.display='none';
            showModal("editStudyplanModalForm");
        }
        else{  // New
            document.querySelector("#add_sp_lname_id").innerHTML=lname;
            document.querySelector("#add_sp_gname_id").innerHTML=gname;
            document.querySelector("#add_sp_lid").value=lid;
            document.querySelector("#add_sp_gid").value=gid;
            document.getElementById("addStudyplanModalForm").querySelector('.errors').style.display='none';
            showModal("addStudyplanModalForm");
        }
    }
}

async function addSp(){
    let fetch_file=document.getElementById('add_sp_route').value;
    let lid=document.querySelector("#add_sp_lid").value;
    let gid=document.querySelector("#add_sp_gid").value;
    let q=document.querySelector("#add_sp_q").value;
    let eid=document.querySelector("#add_sp_employer_id").value;

    let params={gid: gid, lid:lid, eid:eid, q:q};
    let sp=await sendData(fetch_file,params);

    if(sp.status==1){
        let group=arrGroups.find(item=>item.id==gid);
        let lname=arrLessons.find(item=>item.id==lid).name;
        let empl=arrEmployers.find(item=>item.id==eid);
        let pid=sp.pid;

        arrPlan.push({classe_id:group.classe_id, eid: eid, fio: empl.fio, gid:group.id, gname:group.name, ind:group.ind, lid:lid, name: lname, num:group.num, pid:pid, quantity: q });

        const cell=document.querySelector('[data-lid="'+lid+'"][data-gid="'+gid+'"]');
        cell.setAttribute('data-q',q);
        cell.setAttribute('data-pid',pid);
        cell.setAttribute('data-eid',eid);
        cell.innerHTML=q;
        hideModal('addStudyplanModalForm');
    }
    else{
        document.getElementById("addStudyplanModalForm").querySelector('.errors').style.display='block';
        document.getElementById("addStudyplanModalForm").querySelector('.errors').innerHTML=sp.error;
    }

}

async function editSp(){
    let fetch_file=document.getElementById('edit_sp_route').value;
    let q=document.querySelector("#edit_sp_q").value;
    let pid=document.querySelector("#edit_sp_pid").value;
    let eid=document.querySelector("#edit_sp_employer_id").value;

    let params={pid: pid, eid:eid, q:q};
    let sp=await sendData(fetch_file,params);

    if(sp.status==1){
        let empl=arrEmployers.find(item=>item.id==eid);
        let spelem=arrPlan.find(item=>item.pid=pid);
        spelem.eid=eid;
        spelem.fio=empl.fio;
        spelem.quantity=q;

        const cell=document.querySelector('[data-pid="'+pid+'"]');
        cell.setAttribute('data-q',q);
        cell.setAttribute('data-eid',eid);
        cell.innerHTML=q;

        hideModal('editStudyplanModalForm');
    }
    else{
        document.getElementById("editStudyplanModalForm").querySelector('.errors').style.display='block';
        document.getElementById("editStudyplanModalForm").querySelector('.errors').innerHTML=sp.error;
    }

}

async function delSp(){
    let answer=confirm('Точно удалить запись?');
    if(answer){
        let fetch_file=document.getElementById('del_sp_route').value;
        let pid=document.querySelector("#edit_sp_pid").value;

        let params={pid: pid};
        let sp=await sendData(fetch_file,params);

        if(sp.status==1){
            arrPlan=arrPlan.filter(item=>item.pid!=pid);

            const cell=document.querySelector('[data-pid="'+pid+'"]');
            cell.setAttribute('data-q','0');
            cell.removeAttribute('data-eid');
            cell.removeAttribute('data-pid');
            cell.innerHTML="";

            hideModal('editStudyplanModalForm');
        }
        else{
            document.getElementById("editStudyplanModalForm").querySelector('.errors').style.display='block';
            document.getElementById("editStudyplanModalForm").querySelector('.errors').innerHTML=sp.error;
        }
    }

}