<html>
  <head>
    <title>mission4-1</title>
    <meta charset="utf-8">
  </head>
  <body>

  <?php
//データベースへ接続
  $dsn = 'データベース名';
  $user = 'ユーザー名';
  $password = 'パスワード';
  $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));



//テーブル作成
  $sql = "CREATE TABLE IF NOT EXISTS tb7test"
  ."("
  ."id INT AUTO_INCREMENT PRIMARY KEY,"
  ."name char(32),"
  ."comment TEXT,"
  ."time TEXT,"
  ."password TEXT"
  .");";
  $stmt = $pdo->query($sql);

//投稿ボタンを押したとき
    if(isset($_POST['postButton'])){		
      if(!empty($_POST['name']) && !empty($_POST['comment'])){
//新規投稿のとき
	if(empty($_POST['number'])){
//テーブルに投稿内容を入力する
	  $sql = $pdo ->prepare("INSERT INTO tb7test(name,comment,time,password) VALUES(:name,:comment,:time,:password)");
	  $sql->bindParam(':name', $name, PDO::PARAM_STR);
	  $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
	  $sql->bindParam(':time', $time, PDO::PARAM_STR);
	  $sql->bindParam(':password', $password, PDO::PARAM_STR);
	  $name = $_POST['name'];
	  $comment = $_POST['comment'];
	  $time = $_POST['time'];
	  $password = $_POST['password'];
	  $sql->execute();
	}
//編集モードのとき
//テーブルに投稿内容を入力する
	else{
	  $id = $_POST['number'];
	  $name = $_POST['name'];
	  $comment = $_POST['comment'];
	  $sql = 'update tb7test set name=:name,comment=:comment where id=:id';
	  $stmt = $pdo->prepare($sql);
	  $stmt->bindParam(':name',$name,PDO::PARAM_STR);
	  $stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
	  $stmt->bindParam(':id',$id,PDO::PARAM_INT);
	  $stmt->execute();
	}

//テーブルをブラウザ表示
	$sql = 'SELECT * FROM tb7test ORDER BY id ASC';
	$stmt=$pdo->query($sql);
	$results=$stmt->fetchAll();
	foreach($results as $row){
	  echo $row['id'].'  ';
	  echo $row['name'].'  ';
	  echo $row['comment'].'  ';
	  echo $row['time'].'<br>';
	}
      }
    }

//削除ボタン押したとき
    if(isset($_POST['deleteButton'])){	
      if(is_numeric($_POST['delete'])){	
//パスワードの確認
	$delId = $_POST['delete'];
	$sql = 'SELECT * FROM tb7test where id=:id';
	$stmt=$pdo->prepare($sql);
	$stmt->bindParam(':id',$delId,PDO::PARAM_INT);
	$stmt->execute();
	$result=$stmt->fetch();
	if($_POST['passDelete'] == $result['password']){
	  $sql = 'delete from tb7test where id=:id';
	  $stmt=$pdo->prepare($sql);
	  $stmt->bindParam(':id',$delId,PDO::PARAM_INT);
	  $stmt->execute();
//削除後、表示
	  $sql = 'SELECT * FROM tb7test ORDER BY id ASC';
	  $stmt=$pdo->query($sql);
	  $results=$stmt->fetchAll();
	  foreach($results as $row){
	    echo $row['id'].'  ';
	    echo $row['name'].'  ';
	    echo $row['comment'].'  ';
	    echo $row['time'].'<br>';
          }
	}else{echo "パスワードの不一致<br>";}
      }
    }

//編集ボタンを押したとき
    if(isset($_POST['editButton'])){
      if(is_numeric($_POST['edit'])){
//パスワードの確認
	$editId = $_POST['edit'];
	$sql = 'SELECT * FROM tb7test where id=:id';
	$stmt=$pdo->prepare($sql);
	$stmt->bindParam(':id',$editId,PDO::PARAM_INT);
	$stmt->execute();
	$result=$stmt->fetch();

	if($_POST['passEdit'] == $result["password"]){
	  $sql = 'SELECT * FROM tb7test where id=:id';
	  $stmt = $pdo->prepare($sql);
	  $stmt->bindParam(':id',$editId,PDO::PARAM_INT);
	  $stmt->execute();
  	  $info = $stmt->fetch(PDO::FETCH_ASSOC);
	}else{echo "パスワードの不一致\n";}
      }
    }
  ?>

    <form action="mission4_tanaka.php" method="post">
    <input type="hidden" name="time" value="<?php echo date("Y/m/d H:i:s") ?>">
    <input type="text" name="name" value="<?php echo htmlspecialchars($info["name"],ENT_QUOTES) ?>" placeholder="名前">
    <input type="text" name="comment" value="<?php echo htmlspecialchars($info["comment"],ENT_QUOTES) ?>" placeholder="コメント">
    <input type="hidden" name="number" value="<?php echo htmlspecialchars($info["id"],ENT_QUOTES) ?>">
    <input type="text" name="password" placeholder="パスワード">
    <input type="submit" value="送信" name="postButton">
<br><br>
    <input type="text" name="delete" placeholder="削除番号">
    <input type="text" name="passDelete" placeholder="パスワード">
    <input type="submit" value="送信" name="deleteButton">
<br><br>
    <input type="text" name="edit" placeholder="編集番号">
    <input type="text" name="passEdit" placeholder="パスワード">
    <input type="submit" value="送信" name="editButton">
  </form>

  </body>
</html>