<!doctype html>
<html>

<head>
    <title>MyDramaList Template BBCode</title>
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
        <input type="text" id="imdb" placeholder="MDLID"><br><br>
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
            url: 'rund.php' ,//url: "http://www.omdbapi.com" ,
            success: function(data){
                console.log(data);
                var temp = '[table="width: 50%, align: center"]\n[tr="bgcolor: #246092"]\n  [td="width: 500, bgcolor: #246092"]\n[CENTER][table="width: 600, align: center"][tr="bgcolor: #FEFCFF"][td][CENTER][B][SIZE=6][FONT=Palatino Linotype][COLOR="#155194"]' + data.Credit.NativeTitle + ' / ' + data.Title + '[/COLOR][/FONT][/SIZE][/B][/CENTER][/td][/tr][/table]\n\n[img]' + data.Poster + '[/img][/center]\n\n[TABLE="align: center"][tr="bgcolor: #FEFCFF"]\n[TR]\n[TD="bgcolor: #0f4077"][CENTER][URL="https://mydramalist.com/' + data.imdbID + '"][SIZE=3][B][COLOR=#FFFFFF]Mydramalist[/COLOR][/B][/SIZE][/URL][/CENTER]\n[/TD]\n[TD="bgcolor: #0f4077"][CENTER][URL="https://asianwiki.com/"][SIZE=3][B][COLOR=#FFFFFF]AsianWiki[/COLOR][/B][/SIZE][/URL][/CENTER]\n[/TD]\n[TD="bgcolor: #0f4077"][CENTER][URL="https://www.hancinema.net/"][SIZE=3][B][COLOR=#FFFFFF]Hancinema[/COLOR][/B][/SIZE][/URL][/CENTER]\n[/TD]\n[/TR]\n[/TABLE]\n\n[table="width: 80%, align: center"][tr="bgcolor: #FEFCFF"][td][B][SIZE=5][FONT=Palatino Linotype][COLOR="#0C090A"][U]DETAILS[/U][/COLOR][/FONT][/SIZE][/B]\n[FONT=Tahoma][COLOR="#0C090A"][B]Drama[/B]: ' + data.Title + '\n[B]Hangul[/B]: ' + data.Credit.NativeTitle + '\n[B]Also Known As[/B]: ' + data.Credit.AlsoKnownAs + '\n[B]Genres[/B]: ' + data.Credit.Genres + '\n[B]Director[/B]: ' + data.Credit.Director + '\n[B]Writer[/B]: ' + data.Credit.Screenwriter + '\n[B]Network[/B]: ' + data.Credit.OriginalNetwork + '\n[B]Episodes[/B]: ' + data.Credit.Episodes + '\n[B]Release Date[/B]: ' + data.Credit.Aired + '\n[B]Runtime[/B]: ' + data.Credit.AiredOn + '\n[B]Duration[/B]: ' + data.Credit.Duration + '\n[B]Content Rating[/B]: ' + data.Credit.ContentRating + '\n[B]Country[/B]: ' + data.Credit.Country + '[/COLOR][/FONT]\n\n[B][SIZE=5][FONT=Palatino Linotype][COLOR="#0C090A"][U]Synopsis[/U][/COLOR][/FONT][/SIZE][/B]\n[FONT=Tahoma][COLOR="#0C090A"]' + data.Plot + '\n[/COLOR][/FONT][/td]\n[/tr][/table]\n\n[table="width: 200, align: center"][tr="bgcolor: #FEFCFF"][td][CENTER][B][SIZE=5][FONT=Palatino Linotype][COLOR="#00006C"]Cast[/COLOR][/FONT][/SIZE][/B][/CENTER][/td][/tr][/table]\n\n[table="width: 70%, align: center"][tr="bgcolor: #FEFCFF"][td][B][COLOR="#000000"][FONT=Century Gothic][SIZE=3][CENTER]' + data.Actors + '\n[RIGHT][SIZE=1]Cast More -- see asianwiki[/SIZE][/RIGHT]\n[/CENTER]\n[/SIZE][/FONT][/COLOR][/B][/td][/tr][/table]\n\n[table="width: 200, align: center"][tr="bgcolor: #FEFCFF"][td][CENTER][B][SIZE=5][FONT=Palatino Linotype][COLOR="#00006C"]Trailer[/COLOR][/FONT][/SIZE][/B][/CENTER][/td][/tr][/table]\n\n[table="width: 70%, align: center"][tr="bgcolor: #FEFCFF"][td][video=youtube;' + ytid + ']' + ytid + '[/video][/td][/tr][/table]\n[/td]\n[/tr][/table]'
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