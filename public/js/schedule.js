let arrSchedule;
let arrLessons;
let arrRooms;
let arrEmployers;
let arrGroups;
let arrPlan;
let activeTeacherPeriod=0;
let activeTeacherEmployer=0;
let arrCheckError=new Array();


async function start(type, fetch_url){

    await getScheduleData(fetch_url);
    if(type=='main'){
        clearSchedule();
        fillSchedule();
        document.querySelector('.schedule').addEventListener('click', clickLesson);
    }

    if(type=='teachers'){
        clearScheduleTeachers();
        fillScheduleTeachers();
        document.querySelector('.schedule').addEventListener('click', clickLessonTeachers);
        document.querySelector('.teacher_plan').addEventListener('click', clickLessonTeachersPlan);

    }

    if(type=='rooms'){
        clearScheduleRooms();
        fillScheduleRooms();
    }
}

async function getScheduleData(fetch_url){
    let schedule=await getData(fetch_url);
    arrSchedule=schedule.schedule;
    arrPlan=schedule.plan;
    arrLessons=schedule.lessons;
    arrRooms=schedule.rooms;
    arrEmployers=schedule.employers;
    arrGroups=schedule.groups;

}

function clearSchedule(){
    const cells=document.querySelectorAll('[data-period][data-class]');
    cells.forEach( cell => {
        cell.innerHTML='';
    });
}

function clearScheduleTeachers(){
    const cells=document.querySelectorAll('[data-period][data-employer]');
    cells.forEach( cell => {
        cell.innerHTML='';
    });
}

function clearScheduleRooms(){
    const cells=document.querySelectorAll('[data-period][data-room]');
    cells.forEach( cell => {
        cell.innerHTML='';
    });
}

function fillSchedule(){
    arrSchedule.forEach(lesson => {
        const cell=document.querySelector('[data-period="'+lesson.period_id+'"][data-class="'+lesson.classe_id+'"]');
        cell.innerHTML=cell.innerHTML+render("mainScheduleCell", lesson);
    });
}

function fillScheduleTeachers(){
    arrSchedule.forEach(lesson => {
        const cell=document.querySelector('[data-period="'+lesson.period_id+'"][data-employer="'+lesson.eid+'"]');
        cell.innerHTML=cell.innerHTML+render("teacherScheduleCell", lesson);
    });
}

function fillScheduleRooms(){
    arrSchedule.forEach(lesson => {
        const cell=document.querySelector('[data-period="'+lesson.period_id+'"][data-room="'+lesson.rid+'"]');
        cell.innerHTML=cell.innerHTML+render("roomScheduleCell", lesson);
    });
}

function clickLesson(event){
    if(event.target.classList.contains('sr_lesson')){
        document.getElementById('add_lesson_period_id').value=event.target.dataset.period;
        document.getElementById('add_lesson_class_id').value=event.target.dataset.class;
        const groupSelect=document.getElementById('add_lesson_group_id');
        groupSelect.innerHTML="";
        const class_groups=arrGroups.filter(obj=>{return obj.classe_id==event.target.dataset.class});
        class_groups.forEach(group=>{
            let opt=document.createElement('option');
            opt.setAttribute('value',group.id);
            opt.innerHTML=group.num+group.ind+' - '+group.name;
            groupSelect.appendChild(opt);
        });
        document.getElementById("addLessonModalForm").querySelector('.errors').style.display='none';
        showModal("addLessonModalForm");

    }
    if(event.target.classList.contains('sr_g_lesson')){
        setSelect("edit_lesson_lesson_id", event.target.dataset.lid)
        setSelect("edit_lesson_room_id", event.target.dataset.rid)
        setSelect("edit_lesson_employer_id", event.target.dataset.eid)

        document.getElementById('edit_lesson_period_id').value=event.target.parentNode.dataset.period;
        document.getElementById('edit_lesson_class_id').value=event.target.parentNode.dataset.class;
        document.getElementById('edit_schedule_id').value=event.target.dataset.sid;

        const groupSelect=document.getElementById('edit_lesson_group_id');
        groupSelect.innerHTML="";
        const class_groups=arrGroups.filter(obj=>{return obj.classe_id==event.target.parentNode.dataset.class});
        class_groups.forEach(group=>{
            let opt=document.createElement('option');
            opt.setAttribute('value',group.id);
            if(group.id==event.target.dataset.group){
                opt.setAttribute('selected',true);
            }
            opt.innerHTML=group.num+group.ind+' - '+group.name;
            groupSelect.appendChild(opt);
        });

        showModal("editLessonModalForm");
    }
}

function clickLessonTeachers(event){

    // Clear prev selected DOM
    if(activeTeacherEmployer!=0 && activeTeacherPeriod!=0){
        document.querySelector('[data-period="'+activeTeacherPeriod+'"][data-employer="'+activeTeacherEmployer+'"]').classList.remove('active_lesson');
        activeTeacherEmployer=0;
        activeTeacherPeriod=0;
        document.querySelector(".teacher_plan_lessons").innerHTML="";
    }

    // Check current DOM
    if(event.target.classList.contains('sc_lesson')){
        activeTeacherEmployer=event.target.dataset.employer;
        activeTeacherPeriod=event.target.dataset.period;
        event.target.classList.add('active_lesson');
    }
    if(event.target.classList.contains('sc_r_lesson')){
        activeTeacherEmployer=event.target.parentNode.dataset.employer;
        activeTeacherPeriod=event.target.parentNode.dataset.period;
        event.target.parentNode.classList.add('active_lesson');
    }
    if(event.target.parentNode.classList.contains('sc_r_lesson')){
        activeTeacherEmployer=event.target.parentNode.parentNode.dataset.employer;
        activeTeacherPeriod=event.target.parentNode.parentNode.dataset.period;
        event.target.parentNode.parentNode.classList.add('active_lesson');
    }

    clearAndFillTeacherPlan();

}

function clearAndFillTeacherPlan(){
    let plan_list=document.querySelector(".teacher_plan_lessons");
    plan_list.innerHTML="";
    let arrEPlan=arrPlan.filter(item => item.eid==activeTeacherEmployer);
    arrEPlan.forEach(item=>{
        let scheduleLessons=arrSchedule.filter(sch_item => (sch_item.gid==item.gid && sch_item.lid==item.lid && sch_item.eid==item.eid));
        item.need=item.quantity-scheduleLessons.length;
        plan_list.innerHTML=plan_list.innerHTML+render("teacherSchedulePlan", item);
    });

}

function clickLessonTeachersPlan(event){
    let needLesson=0;
    let tplDiv=null;

    // Check current DOM
    if(event.target.classList.contains('teacher_plan_lesson')){
        tplDiv=event.target;
    }
    if(event.target.parentNode.classList.contains('teacher_plan_lesson')){
        tplDiv=event.target.parentNode;
    }
    needLesson=tplDiv.dataset.need;

    if(needLesson>0 && activeTeacherPeriod>0 && activeTeacherEmployer>0){
        addTeacherLesson(tplDiv);
    }

}

async function addTeacherLesson(tplElem){
    let fetch_file=document.getElementById('add_lesson_route').value;
    let group=tplElem.dataset.gid;
    let lesson=tplElem.dataset.lid;
    let period=activeTeacherPeriod;
    let classe=tplElem.dataset.cid;
    let employer=activeTeacherEmployer;
    let room=document.getElementById('teacher_plan_room').value;

    let params={pid: period, gid: group, lid:lesson, rid:room, eid:employer};

    let schedule=await sendData(fetch_file,params);

    if(schedule.status==1){
        let gname=arrGroups.find(item=>item.id==group).name;
        let cnum=arrGroups.find(item=>item.id==group).num;
        let cind=arrGroups.find(item=>item.id==group).ind;
        let lname=arrLessons.find(item=>item.id==lesson).name;
        let rnum=arrRooms.find(item=>item.id==room).number;
        let ename=arrEmployers.find(item=>item.id==employer).fio;
        let sid=schedule.sid;
        // Must equal from ScheduleController -> public function getScheduleData(Request $request){
        let newLesson = {sid: sid, period_id: period, gid: group, gname: gname, classe_id: classe, num: cnum, ind: cind, lid: lesson, name: lname, rid: room, number: rnum, eid: employer, fio: ename };
        arrSchedule.push(newLesson);

        const cell=document.querySelector('[data-period="'+period+'"][data-employer="'+employer+'"]');
        cell.innerHTML=cell.innerHTML+render("teacherScheduleCell", newLesson);

        clearAndFillTeacherPlan();
    }

}

function checkLesson(period, group, lesson, room, employer){
    // Check room
    arrCheckError.length=0;
    let filteredSchedule=arrSchedule.filter(sch_item => (sch_item.period_id==period && sch_item.rid==room))
    if(filteredSchedule.length>0)
        arrCheckError.push('Данный кабинет уже используется в это время');

    filteredSchedule=arrSchedule.filter(sch_item => (sch_item.period_id==period && sch_item.eid==employer))
    if(filteredSchedule.length>0)
        arrCheckError.push('Данный преподаватель уже ведет урок в это время');

    return arrCheckError.length==0;
}


async function addLesson(){
    let fetch_file=document.getElementById('add_lesson_route').value;
    let period=document.getElementById('add_lesson_period_id').value;
    let classe=document.getElementById('add_lesson_class_id').value;
    let group=document.getElementById('add_lesson_group_id').value;
    let lesson=document.getElementById('add_lesson_lesson_id').value;
    let room=document.getElementById('add_lesson_room_id').value;
    let employer=document.getElementById('add_lesson_employer_id').value;

    let resCheckLesson=checkLesson(period, group, lesson, room, employer);
    if(!resCheckLesson){
        // alert('There is some errors!');
        let errdiv = document.getElementById('addLessonModalForm').querySelector('.errors');
        errdiv.innerHTML='';
        let errList=document.createElement('ul');
        arrCheckError.forEach(err=>{
            let errItem=document.createElement('li')
            errItem.innerText=err;
            errList.appendChild(errItem);
        });
        errdiv.appendChild(errList);
        errdiv.style.display='block';
        return;
    }

    let params={pid: period, gid: group, lid:lesson, rid:room, eid:employer};
    hideModal('addLessonModalForm');
    let schedule=await sendData(fetch_file,params);

    if(schedule.status==1){
        let gname=arrGroups.find(item=>item.id==group).name;
        let lname=arrLessons.find(item=>item.id==lesson).name;
        let rnum=arrRooms.find(item=>item.id==room).number;
        let ename=arrEmployers.find(item=>item.id==employer).fio;
        let sid=schedule.sid;
        // Must equal from ScheduleController -> public function getScheduleData(Request $request){
        arrSchedule.push({sid: sid, period_id: period, gid: group, gname: gname, classe_id: classe, lid: lesson, name: lname, rid: room, number: rnum, eid: employer, fio: ename });

        const cell=document.querySelector('[data-period="'+period+'"][data-class="'+classe+'"]');
        let g_lesson=document.createElement('div');
        g_lesson.classList.add('sr_g_lesson');
        g_lesson.setAttribute('data-group',group);
        g_lesson.setAttribute('data-sid',sid);
        g_lesson.setAttribute('data-rid',room);
        g_lesson.setAttribute('data-eid',employer);
        g_lesson.setAttribute('data-lid',lesson);
        g_lesson.innerHTML=lname+' ('+rnum+') <br>'+ename;
        cell.appendChild(g_lesson);
    }

}

async function editLesson(){

    let answer=confirm('Точно внести изменения?');
    if(answer){
        let fetch_file=document.getElementById('edit_lesson_route').value;
        let sid=document.getElementById('edit_schedule_id').value;
        let group=document.getElementById('edit_lesson_group_id').value;
        let lesson=document.getElementById('edit_lesson_lesson_id').value;
        let room=document.getElementById('edit_lesson_room_id').value;
        let employer=document.getElementById('edit_lesson_employer_id').value;

        let params={sid: sid, gid: group, lid:lesson, rid:room, eid:employer};
        hideModal('editLessonModalForm');
        let schedule=await sendData(fetch_file,params);

        if(schedule.status==1){
            let gname=arrGroups.find(item=>item.id==group).name;
            let lname=arrLessons.find(item=>item.id==lesson).name;
            let rnum=arrRooms.find(item=>item.id==room).number;
            let ename=arrEmployers.find(item=>item.id==employer).fio;

            arrSchedule.forEach((value, index, array) => { if(value.sid==sid){ array[index].gid = group; array[index].gname = gname;
                                                                           array[index].lid = lesson; array[index].name = lname;
                                                                           array[index].rid = room; array[index].number = rnum;
                                                                           array[index].eid = employer; array[index].fio = ename; } });

            let g_lesson=document.querySelector('[data-sid="'+sid+'"]');
            g_lesson.setAttribute('data-group',group);
            g_lesson.setAttribute('data-rid',room);
            g_lesson.setAttribute('data-eid',employer);
            g_lesson.setAttribute('data-lid',lesson);
            g_lesson.innerHTML=lname+' ('+rnum+') <br>'+ename;
        }
        else{
            alert('Запись по какой-то причине не отредактирована.');
        }
    }

}

async function delLesson(){
    let answer=confirm('Точно удалить запись?');
    if(answer){
        let fetch_file=document.getElementById('del_lesson_route').value;
        let sid=document.getElementById('edit_schedule_id').value;

        let params={sid: sid};
        hideModal('editLessonModalForm');
        let schedule=await sendData(fetch_file,params);

        if(schedule.status==1){
            arrSchedule = arrSchedule.filter(item => item.sid !== sid);
            document.querySelector('[data-sid="'+sid+'"]').remove();
        }
        else{
            alert('Запись по какой-то причине не удалена.');
        }
    }

}