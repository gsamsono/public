/*
   this class was created by Colin Luther and edited by Grace Samsonow
   CS 205 final project, spring 2015
   
   The Game class contains most of the back-end mechanics of the
   Moose in the House game.
*/

import java.util.Scanner;
import javax.swing.*;
import java.awt.*;
import java.awt.event.*;
import java.io.*;
import javax.imageio.ImageIO;
import java.awt.image.BufferedImage;
import java.net.*;
import java.awt.geom.*;
import java.awt.GraphicsConfiguration;
import java.awt.image.*;
import javax.imageio.*;
import javax.swing.ImageIcon;
import java.util.*;
import java.util.Random;

public class Game
{
   //Initialize Variables for Game 
   int numPlayers; 
   Player[] players; 
   Deck deck;  
   DiscardPile discardPile; 
   String filename;

   
   /*
      default Constructor - creates the game
      numPlayers = the number of players in the game.
      tmg = the GUI object.
   */
   public Game(int numPlayers, TestMooseGUI tmg) throws FileNotFoundException
   {
      this.numPlayers = numPlayers;
      players = new Player[numPlayers];
      for(int i = 0; i < numPlayers; i++){
         players[i] = new Player();//Create player objects and store in array.
      }
      deck = new Deck();
      deck.shuffle();
      discardPile = new DiscardPile();
      for(int i = 0; i < numPlayers; i++){
         for(int j = 0; j < 4; j++){
            players[i].hand.draw(deck);//Each player initially draws 4 cards.
         }
      }
      tmg.setPlayers(players);//set the number of players in the GUI
      tmg.setHandCard(players[0]);//show the users (player 0) hand cards
      gameProcess(tmg);//Begin game.
   }
   
   /*
      GameProces allows each player to take turns while the deck is not empty. 
      tmg = the GUI object.
   */
   public void gameProcess(TestMooseGUI tmg) throws FileNotFoundException
   {
      //Initialize variables
      int numPasses = 0;//the players who cant make a move, and pass
      while(numPasses < numPlayers || !deck.isEmpty()){//Cycle through players while at least 1 player hasn't passed.
         numPasses = 0;
         for(int i = 0; i < numPlayers; i++)//for each player
         {
            try {
               numPasses += turn(i, tmg);//Player i takes his/her turn.
            }//ends try
            catch (IOException ioe) {
               throw new FileNotFoundException("An image was not found. Program closing.");
            }//ends catch
            delay(2000);//Add 2 second delay after each turn so that AI isn't too fast for user.
         } //ends for loop
      }//ends while loop
      try {
      winner win; //creates a new winner object, to determine the winner
      stats stats; //creates a new stats object
      String result = ""; //to hold the winner message
      
      if (numPlayers == 2){ //different constructors depending on the number of players
         win = new winner(players[0], players[1]);//send the player objects to the winner constructor
         result = win.winner();//to display in the GUI
         stats = new stats(players[0], players[1], win.getWinner());//send the player objects to the stats constructor
         stats.statistics(win.getWinner());//performs the stats on the object
         System.out.println("winner: player "+ win.getWinner());//for testing
      }
      if (numPlayers == 3){
         win = new winner(players[0], players[1], players[2]);//send the player objects to the winner constructor
         result = win.winner();//to display in the GUI
         stats = new stats(players[0], players[1], players[2], win.getWinner());//send the player objects to the stats constructor
         stats.statistics(win.getWinner());//performs the stats on the object
         System.out.println("winner: player "+ win.getWinner());//for testing
      }
      if (numPlayers == 4){
         win = new winner(players[0], players[1], players[2], players[3]);//send the player objects to the winner constructor
         result = win.winner();//to display in the GUI
         stats = new stats(players[0], players[1], players[2], players[3], win.getWinner());//send the player objects to the stats constructor
         stats.statistics(win.getWinner());//performs the stats on the object
         System.out.println("winner: player "+ win.getWinner());//for testing
      }
      tmg.setGameStatusLabel(result);//update the game status label w/ the winner
      tmg.setStatsButtonVisible(true);//show the stats button so the user can click it
      //call stats class
      } catch(IOException e){
           System.out.println("The file does not exist " + e);
      }
   }//ends game process func
   
   /*
      The turn method contains all of the functionality used on each turn.
      playerIndex = the index in the array of players, representing the turn player
      tmg = the GUI object.
   */
   public int turn(int playerIndex, TestMooseGUI tmg) throws FileNotFoundException
   {
      int pass = 1;
      startTurnDraw(playerIndex, tmg);//Draw card at beginning of turn. 
             
      for(int i = 0; i < 5; i++){//See if there are moves possible in the turn player's hand.       
         if(hasPossibleMoves(players[playerIndex].hand.getCard(i).getType(), playerIndex)){
            pass = 0;
         }  
      } //ends for loop
      
      //Turn player executes a move or discards a card.
      if(pass == 0){//If a move can be made.
         executeMove(playerIndex, tmg);
      }else{//If there are no valid moves, then discard a card
         if(playerIndex == 0){//If the turn player is a human.
            /*   Player chooses a card to discard. */
            //set the game status label to prompt the user to select a card
            tmg.setGameStatusLabel("No possible moves, select a card to discard and click the discard button.");
            int handCard = 99; //the value when no card is selected
            tmg.clearButtons();//clear the buttons
            boolean discardB = false; //if the discard button is pressed
            while ( (handCard == 99) || (discardB  == false) )//while no card or the discard button is selected
            {
               handCard = tmg.getSelectedHandButton(); //returns an int representing the hand card selected
               discardB = tmg.getDiscardButtonStatus(); //if the discard button is pressed
               //set the game status label to prompt the user to discard a card
               tmg.setGameStatusLabel("No possible moves, select a card to discard and click the discard button.");
            }
            if ( (handCard != 99) && (discardB == true) )//if the user has selected a card and pressed the discard button
            {
               tmg.setGameStatusLabel("Your turn in progress."); //set the game status label
               //System.out.println("selected card to discard: "+ handCard); //for testing
               String filename = players[playerIndex].hand.getCard(handCard).getImage();//the img of the card to discard
               //System.out.println("card filename: "+ filename); //for testing
               tmg.changeDiscardPileIcon(filename); //changes the icon on the discard pile
               players[playerIndex].hand.discard(handCard, discardPile); //discards the selected card
               tmg.removeHandImage(handCard); //remove the image from the users hand
               delay(1000);//delay
            }
            tmg.clearButtons();//clear the buttons after every turn by the user
         } //ends if(playerIndex==0) (human)
         
         else{//(computer player) If computer has no moves, it discards a random card.
         tmg.clearButtons();
         tmg.setGameStatusLabel("Opponents turn in progress."); //set the game status label
            for(int i = 0; i < 5; i++){//look thru the hand
               if(players[playerIndex].hand.getCard(i).getType() != -1){
                  String filename = players[playerIndex].hand.getCard(i).getImage();//the img of the card to discard
                  tmg.changeDiscardPileIcon(filename); //changes the icon on the discard pile
                  players[playerIndex].hand.discard(i,discardPile);//discards the card
                  break;
               }
            } //ends for i loop
            delay(1000);//Add delay
         }//ends else (comput player)
      } // ends else{//If there are no valid moves
      return pass;
   } //ends turn function
   
   /*
      Performs turn player draw at beginning of EACH turn and checks for unusable "moose in the house" cards.
      playerIndex = the index in the array of players, representing the current turn player
      tmg = the GUI object
   */
   public void startTurnDraw(int playerIndex, TestMooseGUI tmg) throws FileNotFoundException {
      int criticalMoose = 0; //moose in the house cards
      
      //Count non-turn players who already have a moose in their house.
      for(int i = 0; i < numPlayers; i++){
         if( (playerIndex != i) && (players[i].house.hasMoose() == true) ){
            criticalMoose++;//increment the count
         }//ends if loop
      } //ends for loop
        
      if(criticalMoose == numPlayers - 1){//If every other player has a moose in their house.
         boolean newMoose = true;//if the player has an unplayable moose in the house card
         do{ 
            newMoose = false; 
            String name = " ";
            players[playerIndex].hand.draw(deck);
            tmg.setHandCard(players[0]); //only set the hand card imgs for the user     
            for(int i = 0; i < 5; i++){//For each card in the turn player's hand.
               if(players[playerIndex].hand.getCard(i).getType() == 0){//If a moose in the house was added.
                  newMoose = true;//Declare that another moose was drawn.
                  if (playerIndex+1 == 1){ //the names of the players
                     name = "you";
                  }
                  if (playerIndex+1 == 2){
                     name = "Mooseolini";
                  }
                  if (playerIndex+1 == 3){
                     name = "Lady Antlerbellum";
                  }
                  if (playerIndex+1 == 4){
                     name = "Moosetapha";
                  }
                  tmg.setGameStatusLabel("An unplayable 'Moose in the House' card was drawn by "+
                        name+" and then discarded."); //set the game status label
                  String filename = "card_imgs/MOOSE_IN_HOUSE.jpg"; //the img of the card to discard
                  //System.out.println("card filename: "+ filename); //for testing
                  tmg.changeDiscardPileIcon(filename); //changes the icon on the discard pile
                  delay(1000);
                  players[playerIndex].hand.discard(i , discardPile);//Discard it.
                       
               } //ends If a moose in the house was added.
            }//ends for loop
         }while(newMoose == true); //ends do
      } //ends If every other player has a moose in their house.
      else{ //If a "Moose in the House" card could still be played.
         players[playerIndex].hand.draw(deck);//Turn player draws.
         tmg.setHandCard(players[0]); //only set the hand card imgs for the user
      }    
   } //ends startTurnDraw func
   
   /*
      If player is human, allow player to choose a move. If the player is a computer, execute a valid move.
      playerIndex = the index in the array of players, representing the current turn player
      tmg = the GUI object
   */
   public void executeMove(int playerIndex, TestMooseGUI tmg) throws FileNotFoundException
   {
      boolean validMove = false;
      int playCardIndex = -1;       //the victims house card to be played on
      int victimPlayerIndex = -1;   //the victim player
      tmg.clearButtons();           //clear buttons
      if(playerIndex == 0){         //human
         while(validMove == false){ //keep trying until a valid move is executed
            tmg.clearButtons();
            playCardIndex = -1;     //reset these variable
            victimPlayerIndex = -1;
            
            /* Player chooses card to play and a victim to play against. */
            //set the game status label to tell the user what to do
            tmg.setGameStatusLabel("Select a card from your hand to play.");
            int handCard = 99; //when no card is selected
            while (handCard == 99)
            {
               handCard = tmg.getSelectedHandButton(); //returns an int representing the hand card selected
               //set the game status label to prompt the user
               tmg.setGameStatusLabel("Select a card from your hand to play.");
            }
            if (handCard != 99) //once a hand card is selected
            {
               //System.out.println("selected card to play: "+ handCard); //for testing
               tmg.setGameStatusLabel("Select a house to play the card on."); //set the game status label
               
               while( (victimPlayerIndex == -1) || (playCardIndex == -1) ) //while no victim is select
               {
                  tmg.setGameStatusLabel("Select a house to play the card on."); //set the game status label
                  victimPlayerIndex = tmg.getVictimPlayer(); //returns an int, gets the selected victim player
                  playCardIndex = tmg.getVictimHouse(); //returns an int, gets the selected victim players house spot
               }
            } //ends if
            tmg.setGameStatusLabel("Your turn in progress."); //set the game status label
            
            switch(players[playerIndex].hand.getCard(handCard).getType()){ //gets the type of the card to be played
               case 0://MITH, Moose in the house
                  if((players[victimPlayerIndex].house.hasMoose() == false) && (victimPlayerIndex != 0))
                  {  //Victim doesn't have MITH and isnt the turn player
                     players[victimPlayerIndex].house.setMooseHouse(true); //set MITH to true
                     players[playerIndex].hand.remove(handCard);//remove the card from the users hand
                     filename = "card_imgs/MOOSE_IN_HOUSE.jpg";
                     tmg.addMITHHouseImage(victimPlayerIndex, filename); //put img on the victims house
                     tmg.removeHandImage(handCard); //remove the image from the users hand
                     validMove = true;
                  }
                  else {
                     tmg.badMovePopup(); //notifies the user that the move was invalid
                     while(tmg.getPopupStatus() == false) 
                     { //window is not closed
                         delay(1000); //wait for user to close it
                     }
                     if (tmg.getPopupStatus() == true ) // when popup window is closed
                        { break; }
                  } //ends else
                  break;
                  
               case 1: case 2: case 3: case 4://ER, empty room
                  if((players[victimPlayerIndex].house.getEmptyRooms() < 3) && (victimPlayerIndex != 0)){
                  //Victim has room for empty rooms and isnt the turn player
                  int location; //where we're going to put the ER in the house array 
                  if (playCardIndex == 0){// when the user tries to put an ER in the wrong space in the victims house
                     location = playCardIndex;
                     tmg.badMovePopup();//notifies the user that the move was invalid
                     while(tmg.getPopupStatus() == false) 
                     { //window is not closed
                         delay(1000); //wait for user to close it
                     }
                     if (tmg.getPopupStatus() == true ) // when popup window is closed
                        { break; }
                  }
                  else {// if (playCardIndex != 0)
                     location = playCardIndex-1;
                  }
                     Card tempCard = new Card(players[playerIndex].hand.getCard(handCard).getType());//create a temporary card object
                     int num = players[victimPlayerIndex].house.addRoom(tempCard, location);//add the ER to the victim players house
                     //System.out.println("TEST where ER is placed " + num);//for testing
                     players[playerIndex].hand.remove(handCard);//removes the card from the hand
                     filename = tempCard.getImage(); //get the image filename
                     //System.out.println("tempCards filename " + filename); //for testing
                     tmg.addERHouseImage(numPlayers, victimPlayerIndex, playCardIndex, filename); //put img of ER on the victims house
                     tmg.removeHandImage(handCard);//removes hand cards img
                     validMove = true;
                  }
                  else {
                     tmg.badMovePopup();//notifies the user that the move was invalid
                     while(tmg.getPopupStatus() == false) 
                     { //window is not closed
                         delay(1000); //wait for user to close it
                     }
                     if (tmg.getPopupStatus() == true ) // when popup window is closed
                        { break; }
                  }//ends else
                  break;
                  
               case 5: case 6: case 7: case 8://MR, moose in the room
                  if( (players[victimPlayerIndex].house.hasMoose() == true) && (victimPlayerIndex != 0) ) {
                  //Victim has moose in the house and isnt the turn player
                  int roomType = players[playerIndex].hand.getCard(handCard).getType();//get the hand cards numerical value
                  //System.out.println("TEST "+ players[victimPlayerIndex].house.getRoom(playCardIndex-1).getImage() );//for testing
                     if( ( players[victimPlayerIndex].house.getRoom(playCardIndex-1).getType() ) == (roomType - 4) ) {
                        //make sure victim has matching ER for the MR to go into
                        if(players[victimPlayerIndex].hand.hasTrap() == true){ //if victim has a moose trap, (MT)
                           for(int j = 0; j < 5; j++){//find the MT in the victim players hand
                              if(players[victimPlayerIndex].hand.getCard(j).getType() == 10){ //10 is the MT numerical value
                                 players[victimPlayerIndex].hand.remove(j); //j is the MT, discards it
                                 String ERfilename = players[victimPlayerIndex].house.getRoom(playCardIndex-1).getImage();//gets the ER img
                                 String filename = players[0].hand.getCard(handCard).getImage();//gets the hand cards img
                                 players[0].hand.remove(handCard);//removes the turn players hand card
                                 tmg.removeHandImage(handCard);//removes its img
                                 tmg.addERHouseImage(numPlayers, victimPlayerIndex, playCardIndex, filename); //adds the MR to the house briefly
                                 delay(1000);//delay so user can see the move in progress
                                 tmg.changeDiscardPileIcon(filename); //puts the MR on the discard pile
                                 filename = "card_imgs/MOOSE_TRAP.jpg";
                                 tmg.addERHouseImage(numPlayers, victimPlayerIndex, playCardIndex, filename); //adds the MT to the house briefly
                                 delay(1000);//delay so user can see the move in progress
                                 tmg.changeDiscardPileIcon(filename); //puts the MT image on the discard pile
                                 tmg.addERHouseImage(numPlayers, victimPlayerIndex, playCardIndex, ERfilename); //puts the ER img back on the victims house
                                 delay(1000);//delay so user can see the move in progress
                                 break;
                              } //ends if card ==10
                           } //ends for loop w/ j
                         players[victimPlayerIndex].hand.draw(deck); //draw after the MT was played
                        } //ends if victimPlayer has a trap
                        else{ //if victim player doesnt have a trap
                           String filename = players[playerIndex].hand.getCard(handCard).getImage();//the MR card to play
                           //System.out.println("filename in MR played by user: "+filename);// for testing
                           players[playerIndex].hand.remove(handCard);//removes the turn players hand card
                           tmg.removeHandImage(handCard);//removes its img
                           tmg.addERHouseImage(numPlayers, victimPlayerIndex, playCardIndex, filename); //puts the MR img on the house
                           players[victimPlayerIndex].house.fillRoom(playCardIndex-1);//fills the room in the house array
                           tmg.setMITRcount(); //sets the label on the players house
                           delay(1000);//delay so user can see the move in progress
                           filename = "card_imgs/ERspace2.gif";
                           tmg.addERHouseImage(numPlayers, victimPlayerIndex, playCardIndex, filename); //puts the empty space img on the house
                        } //ends else (no trap)
                        validMove = true;
                        break;
                     } //ends if ... == (roomType - 4)  (MR played on valid ER)
                     else {//invalid move (MR played on non-matching ER)
                        tmg.badMovePopup(); //notifies the user that the move was invalid
                        while(tmg.getPopupStatus() == false) 
                           { //window is not closed
                               delay(1000); //wait for user to close it
                           }
                           if (tmg.getPopupStatus() == true ) // when popup window is closed
                              { break; }
                     }//ends else
                }//ends if player has a MITH && not turn player
                else {//invalid move
                  tmg.badMovePopup(); //notifies the user that the move was invalid
                  while(tmg.getPopupStatus() == false) 
                     { //window is not closed
                         delay(1000); //wait for user to close it
                     }
                     if (tmg.getPopupStatus() == true ) // when popup window is closed
                        { break; }
                  }//ends else
                  break;
                  
               case 9://CD, closed door
                  if( (playerIndex == victimPlayerIndex)&&(players[playerIndex].house.getEmptyRooms() > 0) ){
                  //Victim (the turn player) has >3 empty rooms
                     for(int i = 0; i < 3; i++){ //search thru the spaces in their house
                        if(players[playerIndex].house.getRoom(playCardIndex-1).getType() != -1){ //find an ER
                           players[playerIndex].house.closeRoom(playCardIndex-1);  //close the room
                           players[playerIndex].hand.remove(handCard);//removes the hand card
                           tmg.removeHandImage(handCard);//removes the hand cards img
                           String filename = "card_imgs/CLOSED_DOOR.png";
                           tmg.addERHouseImage(numPlayers, playerIndex, playCardIndex, filename);//shows the CD img briefly
                           tmg.setCDcount(); //sets the label on the players house
                           delay(1000);//delay so user can see the move in progress
                           filename = "card_imgs/ERspace2.gif";
                           tmg.addERHouseImage(numPlayers, playerIndex, playCardIndex, filename); //show the blank space img
                           break;
                        } //ends if card is not null
                     } //ends for i loop
                     validMove = true;
                  } //ends if player has empty rooms
                  else{
                     tmg.badMovePopup(); //notifies the user that the move was invalid
                     while(tmg.getPopupStatus() == false) 
                     { //window is not closed
                         delay(1000); //wait for user to close it
                     }
                     if (tmg.getPopupStatus() == true ) // when popup window is closed
                        { break; }
                  }//ends else
                  break;
                  
               default://Probably a MT, moose trap
                  tmg.badMovePopup(); //notifies the user that the move was invalid
                  while(tmg.getPopupStatus() == false) 
                     { //window is not closed
                         delay(1000); //wait for user to close it
                     }
                     if (tmg.getPopupStatus() == true ) // when popup window is closed
                        { break; }
                  break;         
            } //ends switch
            tmg.clearButtons();
            delay(1000);//so the user can see their card being played
         } //ends while(validMove == false)
      } //ends if(playerIndex == 0) (human player)
      
      else{ //computer
         tmg.clearButtons();
         tmg.setGameStatusLabel("Opponents turn in progress."); //set the game status label
         int playCardType = -1; //numerical value of the card the computer wants to play (MITH, etc)
         for(int i = 0; i < 5; i++){//find a card that can be played and set playCardIndex
            if(hasPossibleMoves(players[playerIndex].hand.getCard(i).getType(),playerIndex)){
               playCardIndex = i; //index of card to play from hand
               playCardType = players[playerIndex].hand.getCard(i).getType();//resets this value
               break;
            }
         }
         switch(playCardType){ //the type of the card to be played
            case 0://MITH, moose in the house
               Random rand = new Random();
               for(int randomNum = rand.nextInt(numPlayers); randomNum < numPlayers; randomNum = rand.nextInt(numPlayers)){
               //for each player, select a random victims
                  if(randomNum != playerIndex && players[randomNum].house.mooseInHouse == false){
                  //victim cant already have a MITH and cant be the turn player
                     players[playerIndex].hand.remove(playCardIndex);//remove the hand card
                     players[randomNum].house.setMooseHouse(true);//set MITH to true
                     filename = "card_imgs/MOOSE_IN_HOUSE.jpg";
                     tmg.addMITHHouseImage(randomNum, filename); //put img on the victims house at card 0 (location for MITH)
                     break;
                  }
               } //ends for loop for each player
               break;
               
            case 1: case 2: case 3: case 4://ER, empty room
               Random rand1 = new Random();
               int randomNum1 = 0;
               for(randomNum1 = rand1.nextInt(numPlayers); randomNum1 < numPlayers; randomNum1 = rand1.nextInt(numPlayers)){
               //for each player, selected randomly
                  //System.out.println("randomNum1: " +randomNum1);//for testing
                  if(randomNum1 != playerIndex && players[randomNum1].house.getEmptyRooms() < 3){
                  //excluding turn player, and victim has <3 ER already
                     Card tempCard = new Card(playCardType);
                     int spot = players[randomNum1].house.addRoom(tempCard); //ad an ER to the victims house
                     //System.out.println("spot in computer turn, empty rm: " +spot);//for testing
                     filename = tempCard.getImage(); //get the image filename
                     //System.out.println("that cards img filename: " +filename);//for testing
                     tmg.addERHouseImage(numPlayers, randomNum1, spot, filename); //put img on the victims house
                     players[playerIndex].hand.remove(playCardIndex); //removes the hand card
                     break;
                  }//ends excluding turn player loop
               }//ends for each player loop
               //System.out.println("randomNum1: " +randomNum1);//for testing
               break;
                   
            case 5: case 6: case 7: case 8://MR, moose in the room
               Random rand2 = new Random();
               int randomNum2 = 0; //initialize the random number to hold the victim player
               outerloop:
               for(randomNum2 = rand2.nextInt(numPlayers); randomNum2 < numPlayers; randomNum2 = rand2.nextInt(numPlayers)){
               //for each player, randomly selected
               if( (randomNum2 != playerIndex) && (players[randomNum2].house.hasMoose() == true) ) {
               //the victim cant be the turn player and must already have a MITH
                  for(int i = 0; i < 3; i++){//for each potential room in victims house
                     if( players[randomNum2].house.getRoom(i).getType() == playCardType -4 ){
                     //make sure the MR matches an ER in the victims house
                        if(players[randomNum2].hand.hasTrap() == true){//if the victim has a MT, moose trap
                        for(int j = 0; j < 5; j++){//find the MT in the victim players hand
                              if(players[randomNum2].hand.getCard(j).getType() == 10){
                                 players[randomNum2].hand.remove(j);//discard(j,discardPile); //j is the MT, discards it
                                 String ERfilename = players[randomNum2].house.getRoom(i).getImage();//gets the ER img
                                 String filename = players[playerIndex].hand.getCard(playCardIndex).getImage();//gets the hand cards img
                                 players[playerIndex].hand.remove(playCardIndex);//removes the turn players hand card
                                 tmg.addERHouseImage(numPlayers, randomNum2, i+1, filename); //adds the MR to the house briefly
                                 delay(1000);//delay so user can see the move in progress
                                 if (randomNum2 == 0){ //if the victim player is the user
                                    tmg.removeHandImage(j);//remove the img of the trap from the users hand
                                 }
                                 tmg.changeDiscardPileIcon(filename); //puts the MR on the discard pile
                                 filename = "card_imgs/MOOSE_TRAP.jpg";
                                 tmg.addERHouseImage(numPlayers, randomNum2, i+1, filename); //adds the MT to the house briefly
                                 delay(1000);//delay so user can see the move in progress
                                 tmg.changeDiscardPileIcon(filename); //puts the MT on the discard pile
                                 tmg.addERHouseImage(numPlayers, randomNum2, i+1, ERfilename); //puts the ER img back on the victims house
                                 delay(1000);//delay so user can see the move in progress 
                                 break;
                              } //ends if card ==10
                           } //ends for loop w/ j
                           players[randomNum2].hand.draw(deck); //draw after the MT was played
                        } //ends if victimPlayer has a trap
                        else{ //if victim player doesnt have a trap
                           String filename = players[playerIndex].hand.getCard(playCardIndex).getImage();//the MR card to play
                           players[playerIndex].hand.remove(playCardIndex);//removes the turn players hand card
                          // System.out.println("filename of MR to play by AI: "+filename);//for testing
                           tmg.addERHouseImage(numPlayers, randomNum2, i+1, filename); //adds the MR img to the house briefly
                           players[randomNum2].house.fillRoom(i);//fill the ER in the house
                           tmg.setMITRcount(); //sets the label on the players house
                           delay(1000);//delay so user can see the move in progress
                           filename = "card_imgs/ERspace2.gif";
                           tmg.addERHouseImage(numPlayers, randomNum2, i+1, filename); //shows the blank space img
                         }//ends else
                       break outerloop;
                     } //ends if ... == (roomType - 4)
                   }//ends for loop w/ i
                 }//ends if player has a MITH && not turn player
               }//ends //for each player
               break;   
                       
            case 9://CD, closed door
               for(int i = 0; i < 3; i++){//For each potential empty room in player's house.
                  if(players[playerIndex].house.getRoom(i).getType() != -1){ //If not null spot
                     players[playerIndex].house.closeRoom(i); //close the room
                     tmg.setCDcount(); //sets the label on the players house
                     players[playerIndex].hand.remove(playCardIndex);//remove the card from the hand
                     String filename = "card_imgs/CLOSED_DOOR.png";
                     tmg.addERHouseImage(numPlayers, playerIndex, i+1, filename);//shows the CD img briefly
                     delay(1000);//delay so user can see the move in progress
                     filename = "card_imgs/ERspace2.gif";
                     tmg.addERHouseImage(numPlayers, playerIndex, i+1, filename);//show the blank spot img
                     break;
                  }//ends if not null spot
               }//ends for each room
               break;
               
            default:
               break;          
         }//ends switch
         delay(1000);//Add 1 second delay after each turn so that AI isn't too fast for user.
      }//ends else (computer player)
   } //ends executeMove func
   
   //delay of 'int time' so the user can see the moves in progress
   public void delay(int time)
   {
      try {
         Thread.sleep(time);//when time = 2000, its a 2 second delay            
      } 
      catch(InterruptedException ex) {
         Thread.currentThread().interrupt();
      }//delay so user can see the move in progress
   }
   
   /*
      Check input card to see if it can be used this turn.
      cardType = the numeric value of the card to be played
      playerIndex = the player whose turn it is
   */
   public boolean hasPossibleMoves(int cardType, int playerIndex){
      boolean hasMove = false;//flag if theres an available move
      switch (cardType){
      
         case 0://moose in the house
            for(int i = 0; i < numPlayers; i++){//for each player
               if( (i != playerIndex) && (players[i].house.mooseInHouse == false) ){//excluding turn player
                  hasMove = true;
               }
            }
            return hasMove; 
            
         case 1: case 2: case 3: case 4://empty room
            for(int i = 0; i < numPlayers; i++){//for each player
               if( (i != playerIndex) && (players[i].house.getEmptyRooms() < 3) ){//not including the turn player
                  hasMove = true;
               }
            }
            return hasMove;
            
         case 5: case 6: case 7: case 8://moose in room 
            for(int i = 0; i < numPlayers; i++){//for each player
               if( (i != playerIndex) && (players[i].house.mooseInHouse == true) ){//not including turn player
                  for(int j = 0; j < 3; j++){//for each potential room
                     if(cardType == players[i].house.getRoom(j).getType() + 4){//if room types match
                        hasMove = true;
                     }
                  }
               }
            }
            return hasMove;
            
         case 9://closed door
            if(players[playerIndex].house.getEmptyRooms() != 0){
               hasMove = true;
            }
            return hasMove;
                        
         case 10://moose trap
            return false;
            
         case -1://no card
            return false;
            
         default://moose trap = 10
            return false;
      }
   }
   
   //main function to create the game and call the other classes   
   public static void main(String [] args) throws FileNotFoundException
   {
      TestMooseGUI tmg; //create new gui object from this class
      int numPlayers = 0; //counter to hold the number of players
      Scanner keyboard = new Scanner(System.in); //create new scanner object
      
      //get how many players will be in the game
      try 
      {
         System.out.println("How many players in the game? (Please enter 2, 3 or 4 only)");
         numPlayers = keyboard.nextInt(); //read the users input and make sure it's valid
         if (!(numPlayers == 2 || numPlayers == 3 || numPlayers == 4))
         {
            System.out.println("Invalid entry, program closing.");
            System.exit(0); //exit if invalid input
         }
      }
      catch (InputMismatchException exc) //exit if theres an exception
      {
         System.out.println("Invalid entry, program closing.");
         System.exit(0);
      }
      
      try 
      {  //calls the GUI class's constructor which creates the game, send it the number of players
         tmg = new TestMooseGUI(numPlayers);
      } 
      catch (FileNotFoundException fnfe) //display exception
      {
         javax.swing.JOptionPane.showMessageDialog(null,fnfe.toString());
         return;
      }
      tmg.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
      tmg.setExtendedState(java.awt.Frame.MAXIMIZED_BOTH); //fill the screen
      //tmg.setSize(2000,800); //this is the entire screen
      tmg.validate();
      tmg.setVisible(true);

      Game game = new Game(numPlayers, tmg);

   } //ends main func  
} //ends class