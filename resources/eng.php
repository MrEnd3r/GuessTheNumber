<?php
# Configurable Messages: English

# Guess Game Abort Messages
$gameaborted = 'Quiz has been aborted!';
$nogameactive = 'There are no active games availible!';

# Guess Game Solution Messages
$normalsolution = 'Normal Game Solution:'; // Solution is displayed for Normal Number, after this message.
$squaresolution = 'Square Game Solution:'; // Solution is displayed for Square Number, after this message.

# Other Messages
$nopermission = 'You do not have permission to execute this command!';
$gamealreadyactive = 'A quiz has already been activated!';

# Normal Guessing Game
$header = '------- Number Quiz -------';
$firstline = 'First, write a number in chat';
$secondline = "between §d{min} §band §d{max}."; // {min} and {max} are the limits of the game
$thirdline = 'If the guesser is correct,';
$fourthline = 'the guesser will be rewarded!';
$bottom = '------- Number Quiz -------';

# Error Messages
$numtoohigh = "You can only use numbers between §d{min} §cand §d{max}!";
$notright = 'Unfortunately, that is not the correct answer!';

# Winner Messages
$congratulation = "Congratulations, {name}".'!';
$rightnumber = "The correct number was: {number}".'.';
$message = "You have been rewarded {count} of {itemname}!";
# {count}: Amount of the item they are given.
# {itemname}: Name of the Prize! (ex: dirt)

# Squares Guessing Game
$qheader = '--- Square Root Quiz ---';
$qfirstline = 'First, write a number in chat';
$qsecondline = "the square root of §d{qnum}".'.';
$qthirdline = 'If the guesser is correct,';
$qfourthline = 'the guesser will be rewarded!';
$qbottom = '--- Square Root Quiz ---';

# Error Message for Squares
$qnotright = 'Unfortunately, that is not the correct square number!';

# Winner Message
$qcongratulation = 'Congratulations, {name}!';
$qrightnumber = "The sqaure root of §9{qnum} §6is §b{numq}"; 
#{qnum} is the output number
#{numq} is the square number

$qwinnermessage = "You have been rewarded {count} of {itemname}!";
# {Count}: Number of price (Item)
# {Itemname}: Name of the price

# Help Message
$advice = 'To participate, your message must only consist of numbers!';
?>
