<html>
<head>
    <meta charset="utf-8">
    <title>Loreal: Feedback form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <link rel="stylesheet" type="text/css" href="../css/feedback_style.css">
    <script src="../canvasjs.min.js"></script>
      <script type="text/javascript">
  window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer",
    {
      title:{
        text: "Feedback Statistics"
      },
      animationEnabled: true,
      legend: {
        cursor:"pointer",
        itemclick : function(e) {
          if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
              e.dataSeries.visible = false;
          }
          else {
              e.dataSeries.visible = true;
          }
          chart.render();
        }
      },
      axisY: {
        title: "Rating"
      },
      toolTip: {
        shared: true,  
        content: function(e){
          var str = '';
          var total = 0 ;
          var str3;
          var str2 ;
          for (var i = 0; i < e.entries.length; i++){
            var  str1 = "<span style= 'color:"+e.entries[i].dataSeries.color + "'> " + e.entries[i].dataSeries.name + "</span>: <strong>"+  e.entries[i].dataPoint.y + "</strong> <br/>" ; 
            total = e.entries[i].dataPoint.y + total;
            str = str.concat(str1);
          }
          str2 = "<span style = 'color:DodgerBlue; '><strong>"+e.entries[0].dataPoint.label + "</strong></span><br/>";
       
          return (str2.concat(str));
        }

      },
      data: [
      {        
        type: "bar",
        showInLegend: true,
        name: "Highest",
        color: "#A0EC37",
        dataPoints: [
        

        { y: 5, label: "PEOPLE DEVELOPER"},
        { y: 5, label: "ENTREPRENEUR"},
        { y: 5, label: "STRATEGIST"},        
        { y: 5, label: "INTEGRATOR"},        
        { y: 5, label: "INNOVATOR"}


        ]
      },
      {        
        type: "bar",
        showInLegend: true,
        name: "Average",
        color: "#37B3EC",          
        dataPoints: [
        { y: 3.87, label: "PEOPLE DEVELOPER"},
        { y: 4.36, label: "ENTREPRENEUR"},
        { y: 4.15, label: "STRATEGIST"},        
        { y: 4.23, label: "INTEGRATOR"},        
        { y: 4.22, label: "INNOVATOR"}


        ]
      },
      {        
        type: "bar",
        showInLegend: true,
        name: "Lowest",
        color: "#EC5637",
        dataPoints: [
        { y: 1, label: "PEOPLE DEVELOPER"},
        { y: 2, label: "ENTREPRENEUR"},
        { y: 2, label: "STRATEGIST"},        
        { y: 2, label: "INTEGRATOR"},        
        { y: 3, label: "INNOVATOR"}

        ]
      }

      ]
    });

chart.render();
}
</script>
</head>
<body>
  <header>
    <h1>L'ORÉAL: HOD_Finance</h1>
    <h4>India</h4>
    <a href="../hr/choose_function.php"><button class="btn btn-sm">Home</button></a>
    <a href="../hr/view_feedback.php"><button class="btn btn-sm">Back</button></a>
  </header>
  
  <div class="row jumbotron" style="background-color: white;">
    <div class="col-lg-12">
      <div class="container">
      <button class="btn btn-sm" data-toggle="collapse" data-target=".collapse">Expand/Collapse</button>
        <table class="table table-responsive table-hover">
          <thead>
                <tr><th></th><th><h4>Competency</h4></th><th><h4>Self</h4></th><th><h4>Team</h4></th><th><h4>Others</h4></th></tr>
            </thead>
            <tbody>

            
                  <tr class="clickable" data-toggle="collapse" id="row1" data-target=".row1">
                  <td><i class="glyphicon glyphicon-plus"></i></td>
                  <td>PEOPLE DEVELOPER</td>
                  <td>0</td>
                  <td>3.54</td>
                  <td>4.25</td>
                  </tr>

                      <tr class="clickable collapse row1" data-toggle="collapse" id="row11" data-target=".row11">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Treats all individuals in a respectful and consistent manner</td>
                      <td>0</td>
                      <td>2.6</td>  
                      <td>3.9</td>
                      </tr>

                        <tr class="collapse row11">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Sets an example in terms of personal integrity</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row11">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Promotes a climate of mutual respect and transparency across all levels</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row11">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Listens and balances directness with empathy</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.67</td>
                        </tr><tr class="collapse row11">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Delivers with integrity both sustainable and short term results</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row11">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Adjusts priorities to take into account the work load of his/her team</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.5</td>
                        </tr>

                      <tr class="clickable collapse row1" data-toggle="collapse" id="row12" data-target=".row12">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Leverages diversity</td>
                      <td>0</td>
                      <td>3.25</td>  
                      <td>4</td>
                      </tr>
                      
                        <tr class="collapse row12">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Seeks to understand others' motives, ambitions and emotions</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row12">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Encourages the team to work with diverse personalities, functions and cultures</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr>

                      <tr class="clickable collapse row1" data-toggle="collapse" id="row13" data-target=".row13">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td> Stimulates learning</td>
                      <td>0</td>
                      <td>4</td>  
                      <td>4.5</td>
                      </tr>

                        <tr class="collapse row13">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Analyzes personal mistakes and failures and learns from them</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row13">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Creates the work environment to stimulate learning and creativity</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row13">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Acquires and transmits beauty expertise gained through accumulated experience</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.67</td>
                        </tr>
                      
                      <tr class="clickable collapse row1" data-toggle="collapse" id="row14" data-target=".row14">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td> Empowers and develops individuals to contribute their best</td>
                      <td>0</td>
                      <td>4.3</td>  
                      <td>4.6</td>
                      </tr>
                
                        <tr class="collapse row14">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Clarifies performance expectations</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row14">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Clarifies development priorities</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row14">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Shows trust and confidence in people’s ability to succeed</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.67</td>
                        </tr><tr class="collapse row14">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Uses a fact-based approach to appraise and gives feedback</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row14">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Spots talent accurately and mentors them for the Group</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.5</td>
                        </tr>

                <tr class="clickable" data-toggle="collapse" id="row2" data-target=".row2">
                    <td><i class="glyphicon glyphicon-plus"></i></td>
                    <td>ENTREPRENEUR</td>
                    <td>0</td>
                    <td>4.18</td>
                    <td>4.55</td>
                    </tr>

                    <tr class="clickable collapse row2" data-toggle="collapse" id="row21" data-target=".row21">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Takes accountability with courage</td>
                      <td>0</td>
                      <td>3.88</td>  
                      <td>4.5</td>
                      </tr>

                        <tr class="collapse row21">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Stands by own decisions and takes responsibility for them</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row21">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Takes responsibility for setbacks and wins</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row21">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Defends ideas with courage and tenacity especially with peers and superiors</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.67</td>
                        </tr><tr class="collapse row21">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Demonstrates in his decision-making adherence to the ethical charter and internal control norms</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.5</td>
                        </tr>

                      <tr class="clickable collapse row2" data-toggle="collapse" id="row22" data-target=".row22">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Builds and manages a customer centric organization</td>
                      <td>0</td>
                      <td>3.84</td>  
                      <td>4.5</td>
                      </tr>

                        <tr class="collapse row22">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Acts as a role model in connecting with consumers / customers through regular field or online visits</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row22">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Develops win/win partnerships with his/her business partners</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row22">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Builds an agile organization to fulfill customer needs</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.67</td>
                        </tr>

                      <tr class="clickable collapse row2" data-toggle="collapse" id="row23" data-target=".row23">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Gives space for initiatives and enables teams to take risks</td>
                      <td>0</td>
                      <td>4.5</td>  
                      <td>4.67</td>
                      </tr>

                        <tr class="collapse row23">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Allows and encourages testing and learning experiences</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row23">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Allows people to take bets and encourages bold approaches</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row23">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Recognizes the right to make mistakes</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.67</td>
                        </tr>

                      <tr class="clickable collapse row2" data-toggle="collapse" id="row24" data-target=".row24">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Delivers with integrity both sustainable and short term results</td>
                      <td>0</td>
                      <td>4.5</td>  
                      <td>4.5</td>
                      </tr>

                        <tr class="collapse row24">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Delivers short term results without jeopardizing long term priorities</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row24">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Achieves sustainable results by building robust business processes
and capabilities
</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr>

                    <tr class="clickable" data-toggle="collapse" id="row3" data-target=".row3">
                    <td><i class="glyphicon glyphicon-plus"></i></td>
                    <td>STRATEGIST</td>
                    <td>0</td>
                    <td>3.84</td>
                    <td>4.46</td>
                    </tr>

                      <tr class="clickable collapse row3" data-toggle="collapse" id="row31" data-target=".row31">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Builds an inspiring and shared vision</td>
                      <td>0</td>
                      <td>4.33</td>  
                      <td>4.33</td>
                      </tr>

                        <tr class="collapse row31">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Inspires others through his/her clear vision of the future</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row31">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Builds a shared vision co-owned by the team</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row31">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Maintains a constant focus on priorities</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.67</td>
                        </tr>

                      <tr class="clickable collapse row3" data-toggle="collapse" id="row32" data-target=".row32">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Creates strategic scenarios for growth</td>
                      <td>0</td>
                      <td>3.5</td>  
                      <td>4.5</td>
                      </tr>

                        <tr class="collapse row32">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Analyzes the environment from various perspectives and looks for
the how and why of events
</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row32">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Explores different business opportunities and strategizes growth </td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row32">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Thinks big</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.67</td>
                        </tr><tr class="collapse row32">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Thinks ahead</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.5</td>
                        </tr>

                      <tr class="clickable collapse row3" data-toggle="collapse" id="row33" data-target=".row33">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Leads transformation by aligning organization and human capabilities</td>
                      <td>0</td>
                      <td>3.75</td>  
                      <td>4.5</td>
                      </tr>

                        <tr class="collapse row33">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Designs a transformation strategy and translates it into a concrete action plan</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row33">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Drives transformation initiatives and mobilizes stakeholders</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row33">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Supports teams to achieve sustainable transformation</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.67</td>
                        </tr><tr class="collapse row33">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Demonstrates resilience and bounces back after difficult situations</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.5</td>
                        </tr>

                      <tr class="clickable collapse row3" data-toggle="collapse" id="row34" data-target=".row34">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Demonstrates sound judgment in decision making</td>
                      <td>0</td>
                      <td>3.75</td>  
                      <td>4.5</td>
                      </tr>

                        <tr class="collapse row34">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Addresses situations in a holistic perspective</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row34">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Combines experience, intuition and fact-based reasoning</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row34">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Reads new and complex situations quickly and decides accordingly</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.67</td>
                        </tr><tr class="collapse row34">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Presents a complex situation in a simple manner</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.5</td>
                        </tr>

                    <tr class="clickable" data-toggle="collapse" id="row4" data-target=".row4">
                    <td><i class="glyphicon glyphicon-plus"></i></td>
                    <td>INTEGRATOR</td>
                    <td>0</td>
                    <td>3.9</td>
                    <td>4.58</td>
                    </tr>

                      <tr class="clickable collapse row4" data-toggle="collapse" id="row41" data-target=".row41">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Fosters a climate of trust and constructive confrontation</td>
                      <td>0</td>
                      <td>4.17</td>  
                      <td>4.67</td>
                      </tr>

                        <tr class="collapse row41">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Allows people to express their point of view</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row41">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Stimulates dialogue between functions</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row41">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Focuses on ideas, facts and does not make it personal</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.67</td>
                        </tr>

                      <tr class="clickable collapse row4" data-toggle="collapse" id="row42" data-target=".row42">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Develops collective performance of the team</td>
                      <td>0</td>
                      <td>3.5</td>  
                      <td>4.63</td>
                      </tr>

                        <tr class="collapse row42">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Empowers team members with clear delegation</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row42">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Ensures team members work together as a team in a supportive climate</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row42">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Celebrates success and gives energy and enthusiasm to the team</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.67</td>
                        </tr><tr class="collapse row42">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Shares experience, information and best practices with generosity</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.5</td>
                        </tr>

                      <tr class="clickable collapse row4" data-toggle="collapse" id="row43" data-target=".row43">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Enhances transversal cooperation</td>
                      <td>0</td>
                      <td>3.67</td>  
                      <td>4.5</td>
                      </tr>

                        <tr class="collapse row43">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Designs working methods and performance criteria that foster cooperation</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row43">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Designs working methods and performance criteria that foster cooperation</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row43">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Recognizes and rewards collective achievements</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.67</td>
                        </tr>

                      <tr class="clickable collapse row4" data-toggle="collapse" id="row44" data-target=".row44">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Mobilizes stakeholders through active networking</td>
                      <td>0</td>
                      <td>4.25</td>  
                      <td>4.5</td>
                      </tr>

                        <tr class="collapse row44">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Builds his/her network within and outside L’Oréal to achieve business objectives</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row44">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Encourages and assists others to develop people network</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr>

                    <tr class="clickable" data-toggle="collapse" id="row5" data-target=".row5">
                    <td><i class="glyphicon glyphicon-plus"></i></td>
                    <td>INNOVATOR</td>
                    <td>0</td>
                    <td>4</td>
                    <td>4.46</td>
                    </tr>

                      <tr class="clickable collapse row5" data-toggle="collapse" id="row51" data-target=".row51">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Puts the consumer as the central focus</td>
                      <td>0</td>
                      <td>4</td>  
                      <td>4.5</td>
                      </tr>

                        <tr class="collapse row51">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Ensures and enables teams to stay connected with evolving consumer needs</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row51">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Inspires teams through his/her keen focus on consumers</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row51">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Shows interest in the culture and rituals of beauty and uses it toachieve local relevance</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.67</td>
                        </tr>

                      <tr class="clickable collapse row5" data-toggle="collapse" id="row52" data-target=".row52">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Challenges the status quo and strives for excellence</td>
                      <td>0</td>
                      <td>4</td>  
                      <td>4.5</td>
                      </tr>

                        <tr class="collapse row52">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Encourage teams to reflect on the ways of working and to continuously improve </td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row52">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Thinks out of the box and generates innovative strategies</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr>

                      <tr class="clickable collapse row5" data-toggle="collapse" id="row53" data-target=".row53">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Innovates beyond the product</td>
                      <td>0</td>
                      <td>4</td>  
                      <td>4.33</td>
                      </tr>

                        <tr class="collapse row53">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Drives innovation with focus on consumer insights</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row53">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Makes sure his/her team integrates new technologies to enhance the consumer beauty journey</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row53">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Encourages a comprehensive approach of innovation, including all key components from start</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.67</td>
                        </tr>

                      <tr class="clickable collapse row5" data-toggle="collapse" id="row54" data-target=".row54">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Seizes what is just starting and opens new ventures</td>
                      <td>0</td>
                      <td>4</td>  
                      <td>4.5</td>
                      </tr>            

                        <tr class="collapse row54">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Identifies beauty trends as they emerge and champions promising ideas</td>
                        <td>0</td>
                        <td>3.88</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row54">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Prefers to take risks rather than missing opportunities</td>
                        <td>0</td>
                        <td>3.84</td>  
                        <td>4.5</td>
                        </tr><tr class="collapse row54">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Stimulates curiosity and external focus</td>
                        <td>0</td>
                        <td>4.5</td>  
                        <td>4.67</td>
                        </tr>

                  </tbody>
        </table>
      </div> <!-- /container -->
    </div>

        <div class="col-lg-12">
      <div class="container">
        <table class="table">
        <thead class="thead-inverse">
          <tr>
            <th><h4 style="font-weight: bold;">Aggregate</h4></th>
            <th><h4 style="font-weight: bold;">Value</h4></th>
          </tr>
        </thead>
        <tbody>
          <tr class="aggregate">
            <td>Self</td>
                        <td>0</td>
          </tr>
          <tr class="aggregate">
            <td>Team</td>
            <td>3.89</td>
          </tr>
          <tr class="aggregate">
            <td>Others</td>
            <td>4.46</td>
          </tr>  
        </tbody>
      </table>
        <div id="chartContainer" style="height: 300px; width: 100%;"></div>
      </div> <!-- /container -->
    </div>

  </div>  
  <script src="../js//jquery.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
</body>
</html>
