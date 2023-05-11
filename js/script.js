let dealsCount = 0;

function removeDiv(element) {
    element.parentNode.removeChild(element);
    dealsCount --;
}
function addDeal() {

    if(dealsCount < 10) {
        dealsCount ++;

        let getLeadSourse = document.getElementById('source').value;



        let div = document.createElement('div');
        div.className = 'deal';

        let customField = null;

        if(getLeadSourse === "Marquiz") {

        customField = `<input type="text" id="quizName" name="quizName" placeholder="Название квиза">`

        } else if(getLeadSourse === "Вк") {

        customField = `<input type="text" id="Vk" name="Vk" placeholder="Название сообщества">`

        }

        div.innerHTML = `
        <span class="close" onclick="removeDiv(this.parentNode)">x</span>

        <input type="text" id="clientName" name="clientName" placeholder="Имя клиента">
        <label for="pipeline">Выберите ЖК:</label>
            <select size="1" id="JkName" name="JkName">
                <option value="КРД Губернский">КРД Губернский</option>
                <option value="КРД Достояние">КРД Достояние</option>
                <option value="КРД Архитектор">КРД Архитектор</option>
                <option value="СТВ Российский">СТВ Российский</option>
                <option value="СТВ Квартет">СТВ Квартет</option>
                <option value="СТВ 1777">СТВ Кварталы 1777</option>
                <option value="РНД Вересаево">РНД Вересаево</option>
                <option value="РНД ЛБ">РНД Левобережье</option>
                <option value="РНД АП">РНД АП</option>
            </select>
            
            ${customField !== null ? customField : ""}
        
        
         
         <label>Номер телефона:</label>
         <input type="text" id="phone" name="phone" required data-inputmask="'mask': '+7 (999) 999-99-99'">`;




        document.getElementById('deals').appendChild(div);


        $('.deal:last').find('#phone').inputmask();



    } else {

        alert("максимум сделок")
    }





}


function clearDeals() {
    document.getElementById('deals').innerHTML = '';
}

function collectDeals() {


    let res = {};
    let deals = [];

    // Получаем выбранный источник сделок
    let leadSource = document.getElementById('source').value;
    // Получаем все элементы с классом "deal"
    let dealElems = document.getElementsByClassName('deal');
    // Проходим по всем элементам и собираем данные о сделках
    for (let i = 0; i < dealElems.length; i++) {
        let clientName = dealElems[i].querySelector('#clientName').value;
        let JkName = dealElems[i].querySelector('#JkName').value;
        let phone = dealElems[i].querySelector('#phone').value;

        let marquiz = dealElems[i].querySelector('#quizName');
        let vk = dealElems[i].querySelector('#Vk');




        if(phone === "") {

            return alert("Один из номеров телефонов не заполнен");


        }

        if(marquiz !== null) {

            deals.push({

                name: clientName,
                jkName: JkName,
                phone: phone,
                marquiz: marquiz.value


            });

        } else if(vk !== null) {

            deals.push({
                name: clientName,
                jkName: JkName,
                phone: phone,
                vk: vk.value


            });


        } else {
            deals.push({
                name: clientName,
                jkName: JkName,
                phone: phone



            });
        }




    }



    res.leadSource = leadSource;
    res.deals = deals;




    $.ajax({
        url: 'https://hub.integrat.pro/api/usiForms/leadCreate.php',
        method: 'POST',
        dataType: 'json',
        data: { FormsData: res },

    });


    location.reload()



}