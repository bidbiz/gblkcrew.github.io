<!doctype html>
<html>

<head>
    <title>IMDB Template BBCode</title>
    <style type="text/css"> 
        body {
            color: #eee;
            background: #333;
        }
         
        a {
            color: #809fff;
        }

        input {
            color: #fff;
            background-color: #1d1f21;
        }

        textarea {
            color: #fff;
            background-color: #1d1f21;
        }
        * {
          box-sizing: border-box;
      }

      input[type=text], select, textarea {
          width: 100%;
          padding: 12px;
          border: 1px solid #ccc;
          border-radius: 4px;
          resize: vertical;
      }

      label {
          padding: 12px 12px 12px 0;
          display: inline-block;
      }

      input[type=submit] {
          background-color: #4CAF50;
          color: white;
          padding: 12px 20px;
          border: none;
          border-radius: 4px;
          cursor: pointer;
          float: right;
      }

      input[type=submit]:hover {
          background-color: #45a049;
      }

      .container {
          border-radius: 5px;
          background-color: #f2f2f2;
          padding: 20px;
      }

      .col-25 {
          float: left;
          width: 25%;
          margin-top: 6px;
      }

      .col-75 {
          float: left;
          width: 75%;
          margin-top: 6px;
      }

      /* Clear floats after the columns */
      .row:after {
          content: "";
          display: table;
          clear: both;
      }

      /* Responsive layout - when the screen is less than 600px wide, make the two columns stack on top of each other instead of next to each other */
      @media screen and (max-width: 600px) {
          .col-25, .col-75, input[type=submit] {
            width: 100%;
            margin-top: 0;
        }
    }
</style>
</head>
<body>
    <center>
        <input type="text" id="imdb" placeholder="IMDBID"><br><br>
        <input type="text" id="ytid" placeholder="YOUTUBEID"><br><br>
        <button id="get">Generate Template</button><br><br>
        <textarea id="template" rows="30" cols="100"></textarea><br>
    </center>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>

    /**
     * EVENT: When the user has clicked the load more button for the comments
     */
     document.getElementById("get").addEventListener("click", function (e) {
        var imdbid = $("#imdb").val();
        var ytid = $("#ytid").val();
        $.ajax({
            type: "POST",
            dataType: "json",
            data: { i: imdbid },//data: { apikey: 'cc77474b', i: imdbid },
            url: 'runnew.php' ,//url: "http://www.omdbapi.com" ,
            success: function(data){
                console.log(data);
                var temp = '[FONT=Lucida Console][TABLE="align: center"]\n[TR]\n[TD][LEFT]\n[URL="https://subscene.com/"][IMG]https://i.ibb.co/X7VhKw0/subscene.png[/IMG][/URL]\n \n[SIZE=3][COLOR="#1a4156"][B]Subtitles for[/B][/COLOR][/SIZE][/LEFT]\n[HR][/HR]\n[TABLE="width: 200, align: center"][TR="bgcolor: #1a4156"][TD][IMG]' + data.Poster + '[/IMG][/TD][/TR][/TABLE]\n[TABLE="width: 200, align: center"][TR="bgcolor: #1a4156"][TD][CENTER][COLOR="#ffffff"][size=4][b] ' + data.imdbRating + '[/b]/10 [img]https://i.imgur.com/sEpKj3O.png[/img](' + data.imdbVotes + ')[/size][/COLOR][/CENTER][/TD][/TR][/TABLE]\n[/TD]\n[TD="width: 200"][URL="https://www.imdb.com/title/' + data.imdbID + '/"][IMG]https://i.postimg.cc/C1wWjdc9/imdb.png[/IMG][/URL] [B][SIZE=5][COLOR="#dcb115"] ' + data.Name + '[/COLOR][/SIZE][/B]\n \n[JUSTIFY][COLOR="#1a4156"] ' + data.info + '\n \n ' + data.Plot + '\n \n' + data.Credit[0] + '\n' + data.Credit[1] + '\n' + data.Credit[2] + '[/COLOR][/JUSTIFY]\n[TABLE][TR="bgcolor: #dcb115"][TD][video=youtube;' + ytid + ']' + ytid + '[/video][/TD][/TR][/TABLE]\n[/TD]\n[/TR]\n[TR]\n[TD][HR][/HR][/TD]\n[TD][HR][/HR][/TD]\n[/TR]\n[/TABLE][/FONT]'
                $("textarea#template").val(temp);
            },
            async:false,
            error: function() {
                return "IMDB not found.";
            }
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });


</script>
</body>

</html>