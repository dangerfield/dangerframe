<?php
	/*
	 * This is the VIEW for the comment form, and the comment list
	 * 
	 * NOTE, this is the only non-XHTML in this file.
	 * It is completely ignored by DangerFrame!
	 */
?>
<html xmlns:df="dangerframe">
	<head>
		<title>Forms Example</title>
		<style>
		
		form div label
		{
			float: left;
			width: 200px;
		}
		form div
		{
			margin-top: 2px;
		}
		form div input, form div textarea
		{
			width: 200px;
		}
		
		</style>
	</head>
	<body>
		<h1>Forms Example</h1>
		<h2>Instructions</h2>
		<p>Fill in the form correctly, and new comment will be displayed after a refresh.</p>
		<p>If filled incorrectly, form will retain values, but not add to the comments</p>
		<h2>Add a new comment</h2>
		
		<form df:id="commentForm" method="post">
		
			<div>
				<label for="name">Name</label>
				<input df:id="name" id="name" type="text" name="name" value=""/>
			</div>
			
			<div>
				<label for="email">Email address</label>
				<input df:id="email" id="email" type="text" name="text2" value=""/>
			</div>
			
			<div>
				<label for="comment">Comment</label>
				<textarea name="comment" rows="5" cols="5" id="comment" df:id="comment"></textarea>
			</div>

			<div>		
				<label for="subscribeOption">Subscribe to our email list?</label>
				<input type="checkbox" name="subscribeOption" id="subscribeOption" df:id="subscribeOption"/>
			</div>	
			
			<div>
				<label for="source">Where did you find out about us?</label>
				<select name="source" id="source" df:id="source"></select>
			</div>
			
			<div>
				<input df:id="submit" type="submit" name="submit" value="Submit!"/>
			</div>
		</form>

		<h2>Existing Comments</h2>
		<ul>
			<li df:id="commentList">
				<div df:id="date"></div>
				<div df:id="name"></div>
				<div df:id="comment"></div>
			</li>
		</ul>

	</body>
</html>