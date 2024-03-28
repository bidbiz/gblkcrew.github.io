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
            data: { i: imdbid },//data: { apikey: 'da96f2e3', i: imdbid },
            url: 'runnew.php' ,//url: "http://www.omdbapi.com" ,
            success: function(data){
                console.log(data);
                var temp = '[TABLE="width: 65%, align: center"]\n[TR="bgcolor: #9a031e"]\n[TD][TABLE="width: 95%, align: center"]\n[TR]\n[TD="bgcolor: #1c306f, align: center"][FONT=Arial Black][COLOR=#FFFFFF][B][SIZE=5][FONT=tahoma][GLOW=#06aed5]' + data.Name + '[/GLOW][/FONT][FONT=arial][SIZE=3]\n[/SIZE][/FONT][/SIZE][/B][/COLOR][B][SIZE=5][FONT=arial][SIZE=3][URL="https://www.imdb.com/title/' + data.imdbID + '/"][COLOR=#ffffff]IMDb[/COLOR][/URL][COLOR=#ffffff] | [/COLOR][URL="LinkSubtitle"][COLOR=#ffffff]Subtitle[/COLOR][/URL][COLOR=#ffffff]' + data.info + '[/COLOR][/SIZE][/FONT][/SIZE][/B][COLOR=#FFFFFF][B][SIZE=5][FONT=arial][SIZE=3][/SIZE][/FONT]\n[/SIZE]\n[/B][/COLOR][/FONT][/TD]\n[/TR]\n[TR="bgcolor: #e9c46a"]\n[TD][TABLE="width: 350, align: center"]\n[TR="bgcolor: #f4a261"]\n[TD][IMG]' + data.Poster + '[/IMG][/TD]\n[/TR]\n[/TABLE]\n[/TD]\n[/TR]\n[/TABLE]\n[TABLE="width: 95%, align: center"]\n[TR]\n[TD="bgcolor: #046865, align: center"][B][SIZE=5][FONT=Trebuchet MS][B][COLOR=#FFFFFF][GLOW=#256789][FONT=tahoma]Plot[/FONT][/GLOW][/COLOR][/B][/FONT][/SIZE][/B][/TD]\n[/TR]\n[TR]\n[TD="bgcolor: #046865, align: left"][QUOTE]' + data.Plot + '[/QUOTE][/TD]\n[/TR]\n[TR]\n[TD="bgcolor: #21a0a0, align: center"][B][SIZE=5][FONT=Trebuchet MS][B][COLOR=#FFFFFF][GLOW=#256789][FONT=tahoma]Trailer[/FONT][/GLOW][/COLOR][/B][/FONT][/SIZE][/B][/TD]\n[/TR]\n[TR]\n[TD="bgcolor: #21a0a0, align: center"][video=youtube;' + ytid + ']' + ytid + '[/video][/TD]\n[/TR]\n[/TABLE]\n[/TD]\n[/TR]\n[/TABLE]'
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