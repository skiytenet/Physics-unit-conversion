$(document).ready(function(){

  generateQuestionAndAnswer();



  $("body").click(function(){
    if (newQuestionAllowed == true)
    {
        $(".answer").each(function(){
          $(this).css("background-color","EDEDED");
        });

      newQuestionAllowed = false;
      generateQuestionAndAnswer();

    }
    else
    {
      $(".answer").each(function(){
        if($(this).css("background-color") == "rgb(0, 128, 0)")
        {
          newQuestionAllowed = true;
          //console.log("here");
        }
      });
    }

  });

});
var prefix = Array("yokto", "zepto", "atto", "femto", "piko", "nano","mikro","milli","centi","deci","SI-Enhet",
                  "deka","hekto","kilo","mega","giga","tera","peta","exa","zetta","yotta");

var factor = Array("10⁻²⁴","10⁻²¹", "10⁻¹⁸", "10⁻¹⁵", "10⁻¹²", "10⁻⁹", "10⁻⁶", "10⁻³", "10⁻²", "10⁻¹", "10⁰",
                  "10¹", "10²", "10³", "10⁶", "10⁹", "10¹²", "10¹⁵", "10¹⁸", "10²¹", "10²⁴");

var symbol = Array("y", "z", "a", "f", "p","n","\xB5","m","c","d","SI-Enhet",
                  "da","h","k","M","G","T","P","E","Z","Y");

var newQuestionAllowed = false;
var questionType = 0;
var tmp;
var answerIsWrong = false;

function generateQuestionAndAnswer()
{
  console.clear();
  questionType++;
  if(questionType == 5)
  {
    questionType = 1;
  }
  var random = null;
  tmp = null;
  var randNumbers = null;

  random = Math.floor((Math.random() * 20));

  console.log("question type "+(questionType));

  randNumbers = shuffle(randomNumbers(random));

  if(questionType == 1)
  {
    var question = factor[random];
    $("#question").html("Vilket benämning har talfaktor: " + question +"");
    $(".answer").each(function(index){
      $(this).text(prefix[randNumbers[index]]);
    //  console.log(prefix[randNumbers[index]]);
    });
    $(".answer").click(function(){
      var answer = prefix.indexOf($(this).text());
      if(answer == random)
      {
        $(this).css("background-color","green");
      }
      else
      {
        $(this).css("background-color","red");
      }
    });
  }

  if(questionType == 2)
  {
    var question = prefix[random];
    $("#question").html("Vilken talfaktor har benämningen: " + question +"");
    $(".answer").each(function(index){
      $(this).text(factor[randNumbers[index]]);
    //  console.log(prefix[randNumbers[index]]);
    });
    $(".answer").click(function(){
      var answer = factor.indexOf($(this).text());
      if(answer == random)
      {
        $(this).css("background-color","green");
      }
      else
      {
        $(this).css("background-color","red");
      }
    });
  }

  if(questionType == 3)
  {
    var question = symbol[random];
    $("#question").html("Vilket benämning har beteckningen: " + question +"");
    $(".answer").each(function(index){
      $(this).text(prefix[randNumbers[index]]);
    //  console.log(prefix[randNumbers[index]]);
    });
    $(".answer").click(function(){
      var answer = prefix.indexOf($(this).text());
      if(answer == random)
      {
        $(this).css("background-color","green");
      }
      else
      {
        $(this).css("background-color","red");
      }
    });
  }

  if(questionType == 4)
  {
    var question = prefix[random];
    $("#question").html("Vilken beteckning har benämningen: " + question +"");
    $(".answer").each(function(index){
      $(this).text(symbol[randNumbers[index]]);
    //  console.log(prefix[randNumbers[index]]);
    });
    $(".answer").click(function(){
      var answer = symbol.indexOf($(this).text());
      if(answer == random)
      {
        $(this).css("background-color","green");
      }
      else
      {
        $(this).css("background-color","red");
      }
    });
  }


}


function randomNumbers(answer)
{
  var randomArr = Array();
  randomArr[0] = answer;
  var random = Math.floor((Math.random() * 20));
  for (var i = 1; i < 4; i++)
  {
    while(randomArr.indexOf(random) != -1)
    {
      random = Math.floor((Math.random() * 20));

    }
  //      console.log(random);
    randomArr[i] = random;
  }

  //  console.log(answer);
  return randomArr;
}

function shuffle(array) {
  var currentIndex = array.length, temporaryValue, randomIndex ;

  // While there remain elements to shuffle...
  while (0 !== currentIndex) {

    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex -= 1;

    // And swap it with the current element.
    temporaryValue = array[currentIndex];
    array[currentIndex] = array[randomIndex];
    array[randomIndex] = temporaryValue;
  }

  return array;
}
