<?php
///www/hub.integrat.pro/api/usiForms/
const ROOT = __DIR__;

require ROOT . "/functions/require.php";

logs();


$input = $_POST["FormsData"];

if (!empty($_GET["test"])) {
    $input = [

        "leadSource" => "Заявка с сайта",
        "deals" => [

            [

            "name" => "23232",
            "jkName" =>  "СТВ 1777",
            "phone" => "+7 (000) 149-22-27",


            ]

        ]
    ];




}


foreach ($input["deals"] as $leadFromForm) {

    $leadSource = $input["leadSource"];





    if($leadSource != "Заявка с сайта") {
        $leadSourceForTask = "Заявка с $leadSource";


    } else {
        $leadSourceForTask = "Заявка с сайта";


    }

    $jkName = $leadFromForm["jkName"];
    $name = trim(mb_strtolower($leadFromForm["name"]));
    $phone = trim(mb_strtolower($leadFromForm["phone"]));






    $pipeline_id = null;
    $status_id = null;
    $respUserId = null;

    $customField = null;

    $tag1 = null;
    $tag2 = null;


    if ($jkName == "РНД Вересаево") {
        $pipeline_id = 1399426;
        $status_id = 22090243;
        $respUserId = 6402732;

    } elseif ($jkName == "РНД ЛБ") {
        $pipeline_id = 4242663;
        $status_id = 46035738;
        $respUserId = 8364786;

    } elseif ($jkName == "КРД Губернский") {
        $pipeline_id = 1393867;
        $status_id = 22041304;
        $respUserId = 3899314;

    } elseif ($jkName == "КРД Достояние") {
        $pipeline_id = 3302563;
        $status_id = 33254971;
        $respUserId = 7874628;

    } elseif ($jkName == "КРД Архитектор") {
        $pipeline_id = 6427297;
        $status_id = 54951493;
        $respUserId = 8796285;

    } elseif ($jkName == "СТВ Российский") {
        $pipeline_id = 1399423;
        $status_id = 22090234;
        $respUserId = 9325605;

    } elseif ($jkName == "СТВ Квартет") {
        $pipeline_id = 5129982;
        $status_id = 46008504;
        $respUserId = 9325605;

    } elseif ($jkName == "СТВ 1777") {
        $pipeline_id = 4551390;
        $status_id = 41960496;
        $respUserId = 9325605;

    } elseif ($jkName == "РНД АП") {
        $pipeline_id = 5771604;
        $status_id = 50630754;
        $respUserId = 6402732;

    }



    if($input["leadSource"] == "Marquiz") {

        $customField = $leadFromForm["marquiz"];
        $tag1 = 428963;
        $tag2 = 423613;


    } else if($input["leadSource"] == "Вк") {

        $customField = $leadFromForm["vk"];
        $tag1 = 428963;
        $tag2 = 410723;

    } else if($input["leadSource"] == "Одноклассники") {

        $tag1 = 428963;
        $tag2 = 475530;

    } else if($input["leadSource"] == "Заявка с сайта") {


        $tag1 = 428963;

        if($leadFromForm["jkName"] == "КРД Губернский") {

//            $leadSource = $leadSource . " mkr-gubernskiy.ru";

            $tag2 = 390239;

        } else if($leadFromForm["jkName"] == "КРД Достояние") {

//            $leadSource = $leadSource . " dostoyanie23.ru";

            $tag2 = 440359;

        } else if($leadFromForm["jkName"] == "КРД Архитектор") {

//            $leadSource = $leadSource . " mkr-gubernskiy.ru";

            $tag2 = 390239;

        } else if($leadFromForm["jkName"] == "СТВ Российский") {

//            $leadSource = $leadSource . " zhk-rossiyskiy.ru";

            $tag2 = 390269;

        } else if($leadFromForm["jkName"] == "СТВ Квартет") {

//            $leadSource = $leadSource . " kvartet-26.ru";

            $tag2 = 456412;

        } else if($leadFromForm["jkName"] == "СТВ 1777") {

//            $leadSource = $leadSource . " marquiz.ru";

            $tag2 = 423613;

        } else if($leadFromForm["jkName"] == "РНД Вересаево") {

//            $leadSource = $leadSource . " veresaevo.ru";

            $tag2 = 390363;

        } else if($leadFromForm["jkName"] == "РНД ЛБ") {

//            $leadSource = $leadSource . " levoberezhe.ru";

            $tag2 = 456754;

        } else if($leadFromForm["jkName"] == "РНД АП") {

//            $leadSource = $leadSource . " levoberezhe.ru";

            $tag2 = 456754;

        }

    }






    if (!empty($pipeline_id)) {

        $contact_id = null;
        $we_have_active_lead = false;
        $phone_to_search = preg_replace("/[^\d]/siu", "", $phone);
        if (mb_strlen($phone_to_search) == 11) {
            $phone_to_search = substr($phone_to_search, 1);
        }


        $existing_contacts = searchEntity(CRM_ENTITY_CONTACT, $phone_to_search);


        if (!empty($existing_contacts["_embedded"]["contacts"])) {

            $contact_id = $existing_contacts["_embedded"]["contacts"][0]["id"];

            foreach ($existing_contacts["_embedded"]["contacts"] as $existing_contact) {

                if (!empty($existing_contact["_embedded"]["leads"])) {
                    foreach ($existing_contact["_embedded"]["leads"] as $existing_lead_link) {
                        $existing_lead = getEntity(CRM_ENTITY_LEAD, $existing_lead_link["id"]);
                        if (in_array($existing_lead["pipeline_id"], [1399426, 4242663, 1393867, 3302563, 6427297, 1399423, 5129982, 4551390, 5771604]) && !in_array($existing_lead["status_id"], [142, 143])) {

                            echo "<h3>Есть активный лид {$existing_lead["id"]}</h3><pre>";
                            $we_have_active_lead = true;
                            break;
                        }
                    }
                    if ($we_have_active_lead) {
                        break;
                    }
                }
            }
        }







        if (!$we_have_active_lead) {

            if (empty($contact_id)) {
                $contact_add_data = [
                    "created_at" => time(),
                    "name" => $name,
                    "responsible_user_id" => $respUserId,
//                    "custom_fields_values" => []
                ];
//                $contact_add_data["custom_fields_values"][] = [
//                    "field_id" => FIELD_ID_PHONE,
//                    "values" => [["value" => $phone, "enum_code" => "MOB"]]
//                ];

                $contact_add = addEntity(CRM_ENTITY_CONTACT, $contact_add_data);




                $contact_id = intval($contact_add["_embedded"]["contacts"][0]["id"]);
            }





            $lead_add_data = [
                "created_at" => time(),
                "name" => "Лид c $leadSource",
                "responsible_user_id" => $respUserId,
                "pipeline_id" => $pipeline_id,
                "status_id" => $status_id,
            ];
            $lead_add_data["_embedded"]["contacts"][]["id"] = $contact_id;

            $lead_add_data["_embedded"]["tags"][]["id"] = $tag1;
            $lead_add_data["_embedded"]["tags"][]["id"] = $tag2;





            $lead_add = addEntity(CRM_ENTITY_LEAD, $lead_add_data);




            if(isset($customField)) {

                addNote("leads", $lead_add["_embedded"]["leads"][0]["id"],
                    $leadSource, $jkName, $name, $phone, $customField);

            } else {

                addNote("leads", $lead_add["_embedded"]["leads"][0]["id"],
                    $leadSource, $jkName, $name, $phone);
            }



            addTask($lead_add["_embedded"]["leads"][0]["id"], $respUserId, $leadSourceForTask);





        } else {

            if(!empty($existing_lead["id"])) {
                addNote("leads", $existing_lead["id"], $leadSource, $jkName, $name, $phone, $customField);
                addTask($existing_lead["id"], $respUserId, $leadSourceForTask);

            }


        }






    }






}


