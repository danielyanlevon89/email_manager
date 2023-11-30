<?php
$SymbolsArray = [
            'I' => 1,
            'IV' => 4,
            'V' => 5,
            'IX' => 9,
            'X' => 10,
            'XL' => 40,
            'L' => 50,
            'XC' => 90,
            'C' => 100,
            'CD' => 400,
            'D' => 500,
            'CM' => 900,
            'M' => 1000
        ];
function getNumberFromRomeToArabic($romeNumber){
    global $SymbolsArray;
    uksort($SymbolsArray, function($a, $b) { return strlen($b) - strlen($a); });
    $arabicNumber = 0;
    while(strlen($romeNumber)>0){
        if(isset($SymbolsArray[substr($romeNumber, 0, 2)])){
            $arabicNumber+= $SymbolsArray[substr($romeNumber, 0, 2)];
            $romeNumber = substr($romeNumber, 2);
        } elseif(isset($SymbolsArray[substr($romeNumber, 0, 1)])){
            $arabicNumber+= $SymbolsArray[substr($romeNumber, 0, 1)];
            $romeNumber = substr($romeNumber, 1);
        } else {
            return 'Unavailable Value';
        }
    }
    return $arabicNumber;
}

echo '<pre>';print_r(getNumberFromRomeToArabic( 'MCDIX' ));


function chunkWhile($string){
    $array = str_split($string);
    $newArray = [];
    $iterArray = [];
    foreach($array as $k => $a){
        if(count($iterArray) > 0 && end($iterArray) != $a){
            $newArray[] = $iterArray;
            $iterArray = [];
        }
        $iterArray[] = $a;

        if( $k == count( $array ) - 1){
            $newArray[] = $iterArray;
        }
    }
    return $newArray;
}

function getSecondOccurrenceSymbolsFromString($string){
    $bigArrays = array_filter(chunkWhile($string),function($value) {
        if(count($value) > 1){
            return $value;
        }
    });

    if(!$bigArrays){
        return false;
    }

    if(count($bigArrays) == 1){
        return $bigArrays[0];
    }

    $occurrenceValue = '';
    foreach($bigArrays as $k=>$ba){
        if($occurrenceValue == $ba[0]){
            return $occurrenceValue;
        }
        $occurrenceValue = $ba[0];
    }

    return $occurrenceValue;
}

echo '<pre>';print_r(getSecondOccurrenceSymbolsFromString('abccddeddfffgiffhhhhhj'));

//Class ChatNotificationManager {
//    function notify($message)l
//}
//
//Class ChatMessageRepository {
//    public function save($message) {
//
//    }
//}
//
//class ChatMessage {
//    private ?int $id;
//    private int $chatId;
//    private UserInterface $user;
//    private string $text;
//
//    public function __construct($chatId,$user,$text) {
//        $this->chatId = $chatId;
//        $this->user = $user;
//        $this->text = $text;
//
//        $chatnotificationManager = new ChatNotificationManager();
//        $chatnotificationManager->notify($message);
//    }
//}
//
//class Chat {
//    public int $id;
//    public ChatMessage[] $message;
//
//    public function addMessage($user,$text){
//        $message = new ChatMessage($this->id,$user,$text);
//        $chatMessageRepository = new ChatMessageRepository();
//        $chatMessageRepository->save($message);
//    }
//}

?>
<style>
    #button {
        border: 1px solid black;
        width: 100px;
        height: 40px;
        overflow: auto;
    }

    #button:hover {
        width: 170px;
        height: 40px;
        border-radius: 50px;
    }
</style>
<button id="button">button 1</button>





<script src="https://cdn.jsdelivr.net/npm/vue@2.7.14/dist/vue.js"></script>

<div id="app">

    <input type="text" @keyUp.enter="addTask" v-model="currentTask">
    <ul>
        <li
            v-for="task in tasks"
            :class="{'strike':task.isCompleted}"
        >
<input
        type="text"
        v-model="editValue"
        v-if="task.isEditing"
        @keyUp.enter="editTask(task.text)"
>
            <span v-else @click="task.isCompleted =! task.isCompleted">{{task.text}}</span>
            <button @click="removeTask(task.text)">X</button>
            <button @click="changeEditing(task.text)">*</button>

        </li>
    </ul>
<button @click="postNow">update city name</button>

    <h1>The City Name is {{cityName}}</h1>
    <input type="text" v-model="cityName">

</div>

<script>
    new Vue({
        el:'#app',
        data:{
            cityName:'Yerevan',
            currentTask:'',
            editValue:'',
            tasks: [
                {
                    'text': 'Learn English',
                    'isCompleted': false,
                    'isEditing' : false
                },
                {
                    'text': 'Learn Programming',
                    'isCompleted': false,
                    'isEditing' : false
                },
                {
                    'text': 'Read Book',
                    'isCompleted': true,
                    'isEditing' : false
                },
            ]

        },
        methods:{
            addTask :function(){
               this.tasks.push({
                   text:this.currentTask,
                   isCompleted:false
               });
               this.currentTask='';
            },
            removeTask :function(taskText){
                this.tasks = this.tasks.filter(task => {
                        return task.text !== taskText;
                    })
            },
            changeEditing :function(taskText){
                this.editValue = taskText;
                this.tasks = this.tasks.map(task => {
                    if(task.text === taskText) {
                        task.isEditing = !task.isEditing;
                    }
                    return task
                })
            },
            editTask :function(taskText){
                this.tasks = this.tasks.map(task => {
                    if(task.text === taskText) {
                        task.isEditing = !task.isEditing;
                        task.text = this.editValue;
                    }
                    return task
                })
            },
            postNow: function() {

                let url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/iplocate/address?ip=";
                let token = "c8952c16604b8902d742adbf35e77aab9874f9a6";
                let query = "46.226.227.20";

                let options = {
                    method: "GET",
                    mode: "cors",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "Authorization": "Token " + token
                    }
                };

               fetch(url + query, options)
                    .then(response => response.text())
                    .then(result =>this.getResult(result))
                    .catch(error => console.log("error", error));

            },
            getResult: function (result){
                let data  = JSON.parse(result);
                this.cityName = data.location.data.city??'undefined city';

            }
        }

    })

</script>
<style>
    .strike{text-decoration: line-through}
    li{cursor: pointer}
</style>



<!--<script>-->
<!--    let url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/iplocate/address?ip=";-->
<!--    let token = "c8952c16604b8902d742adbf35e77aab9874f9a6";-->
<!--    let query = "46.226.227.20";-->
<!---->
<!--    let options = {-->
<!--        method: "GET",-->
<!--        mode: "cors",-->
<!--        headers: {-->
<!--            "Content-Type": "application/json",-->
<!--            "Accept": "application/json",-->
<!--            "Authorization": "Token " + token-->
<!--        }-->
<!--    };-->
<!---->
<!--    fetch(url + query, options)-->
<!--        .then(response => response.text())-->
<!--        .then(result =>getResult(result))-->
<!--        .catch(error => console.log("error", error));-->
<!---->
<!---->
<!---->
<!---->
<!--    function getResult (result){-->
<!---->
<!--        let data  = JSON.parse(result);-->
<!--        console.log(data.location.data.city??'undefined city');-->
<!--//        alert(data.location.value??'undefined city');-->
<!--    }-->
<!---->
<!---->
<!---->
<!--    class ColorfulButton {-->
<!--        constructor(buttonElement) {-->
<!--            this.element = buttonElement;-->
<!--            this.element.classList.add('colorful-button');-->
<!--                this.element.addEventListener('click', (event) => {-->
<!--                    var targetElement = event.target || event.srcElement;-->
<!--                    targetElement.style.transition = 'all 2s';-->
<!--                    targetElement.style.color = this.generateRandomColor();-->
<!--                    targetElement.style.backgroundColor = this.generateRandomColor();-->
<!--                    this.sleep(30000,targetElement);-->
<!--                });-->
<!--        }-->
<!---->
<!--        async sleep(delayTime,targetElement) {-->
<!--            await new Promise(resolve => setTimeout(resolve, delayTime));-->
<!--            targetElement.style.transition = '';-->
<!--        }-->
<!---->
<!--        generateRandomColor() {-->
<!---->
<!--            const rgb = [-->
<!--                this.generateRandomInt(0,255),-->
<!--                this.generateRandomInt(0,255),-->
<!--                this.generateRandomInt(0,255),-->
<!--            ];-->
<!--            return 'rgb('+rgb.join()+')';-->
<!--        }-->
<!---->
<!--         generateRandomInt(start,end) {-->
<!--             start = Math.ceil(start);-->
<!--             end = Math.floor(end);-->
<!--             return Math.round(Math.random() * (end - start) + start);-->
<!--        }-->
<!---->
<!--    }-->
<!---->
<!--    const buttons = document.querySelectorAll('button');-->
<!--    for(const button of buttons){-->
<!--        new ColorfulButton(button);-->
<!--    }-->
<!---->
<!---->
<!---->
<!---->
<!--</script>-->




