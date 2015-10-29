/**
by grace samsonow
CS 205 final project
there's a moose in the house card game
3/3/15
*/

import javax.swing.*;
import javax.swing.text.*;
import java.awt.*;              //for layout managers and more
import java.awt.event.*;        //for action events
import java.net.URL;
import java.io.IOException;
import java.io.*;
import javax.imageio.ImageIO;


public class HelpScreen {
    
    //constructor
    public HelpScreen() {
    
	 /* create the frame:*/
      JFrame frame = new JFrame("Help Screen");

     /* construct and then add the text area to the frame:    */
      JTextArea info = new JTextArea("Objective of the Game:\n"+ 
        "The object of the game is to keep moose out of the rooms of your house, "+
        "put moose into your opponents' houses, and have the fewest moose in "+
        "your house when the last card is played.\n\n" +
        
        "How To Play:\n"+
        "All players start a game with an invisible empty 'house' in front of them "+
        "on the playing surface. As the game progresses, players fill opponents' "+
        "houses with rooms and then their rooms with moose.\n\n"+
        
        "Start each turn by drawing one card from the face down deck. Then take ONE of the following actions: \n"+
        "--Play a There's a Moose in the House card on any opponent that doesn't already have one. \n"+
        "--Play an empty room card on any opponent that has less than three empty rooms.\n"+
        "--Play a matching Moose in the Room card on top of any opponent's empty room "+
        "(providing they have a There's a Moose in the House card in front of them). \n"+
        "--Play a Door card on one of your empty rooms. \n"+
        "--If you can't play any of your cards, simply discard on into a face up pile "+
        "next to the draw pile. This ends your turn, and play moves to the next player.\n\n"+
        
        "Card Actions:\n\n"+

        "There's a Moose in the House:\n" +
        "This is a very important card early in the game, as you cannot put any moose into opponents' "+
        "houses until they have one of these cards in front of them. "+
        "Once you get a There's a Moose in the House card, \nit stays for the duration of the game. "+
        "You can't stop anyone from giving this card to you and you can't get rid of it. "+
        "However, each player may only get one There's a Moose in the House card during the game.\n"+
        
        "Note:\n"+
        "Once all players have a There's a Moose in the House card in front of them, discard any extra "+
        "There's a Moose in the House cards from you hand, and draw new cards to bring it back up to four.\n"+
        "Also, if you draw a There's a Moose in the House card after all players have a There's a Moose in the House "+
        "card in front of them simply discard it and draw another card.\n\n"+
        
        "Empty Rooms:\n"+
        "There are four different empty rooms in the house: a kitchen, living room, bedroom and bathroom. During "+
        "your turn, try to give one of these cards to another player. \nOnce you get an Empty Room card, opponents may "+ 
        "play a matching Moose in the Room card on top of it during subsequent turns, unless you close it first "+
        "using a Door card (see below.) \nEmpty Room cards are the only cards you can give opponents before they "+
        "have a There's a Moose in the House card in front of them.\n"+
        "Note:\n"+
        "You can have multiple matching rooms in your house (for example, two kitchens, three bedrooms, etc).\n"+
        "You cannot have more than three empty rooms (without a Closed Door or Moose) in your house at once during the game.\n\n"+
        
        "Moose in the Room:\n"+
        "Each empty room has a matching Moose in the Room Card. Once opponents have a There's a Moose in the House "+
        "card, you can put moose in there rooms. \nLook at the Empty Room cards in front of your opponents. If "+
        "someone has an Empty Room card that matches a Moose in the Room card from your hand, place the card face "+
        "up on top of the Empty Room card.\n"+
        "Note:\n"+
        "You may only play Moose in the Room cards on opponents who have a There's a Moose in the House "+
        "Card in front of them. \nAlso, you can only play one Moose in the Room card per Empty Room.\n\n"+
        
        "Door:\n"+
        "Door cards keep moose out of the rooms in your house. Play a Door card face up on top of any Empty Room "+
        "card in front of you. Doors permanently shut rooms and stay in place until the end of the game.\n"+
        "Note:\n"+
        "This is the only card that you may play to your house and you may only play it on an empty room.\n\n"+

        "Moose Trap:\n"+
        "When someone tries to put a moose in one of your empty rooms, play this card immediately from your hand. "+
        "Take both the Moose Trap card along with the opponent's Moose in the Room card and place them in the "+
        "discard pile. \n(Leave your Empty Room card as players may still try to put another moose there later in the "+
        "game.) Then, draw another card to bring your hand back up to four cards.\n"+
        "Note:\n"+
        "You can only play this card as an instant action during someone else's turn, not during your turn.\n\n"+

        "Ending the Game:\n"+
        "When the last card is drawn from the deck, continue playing out the cards in your hand. Take turns a "+
        "usual, playing one card at a time. If you can't play a card from your hand, say 'pass'. \nKeep going "+
        "until all players are either out of cards or have to pass. At this point, add the total "+
        "number of Moose in the Room cards, both in your hand and in your house. \nThe player with the fewest Moose "+
        "wins. (In the case of a tie, the player with the most closed doors wins.)\n");


      info.setLineWrap(true); //allow word wrapping
      info.setWrapStyleWord(true);
      info.setEditable(false); //do not allow the user to edit the instructions
      
      //create a pane to allow for scrolling
      JScrollPane areaScrollPane = new JScrollPane(info);
      areaScrollPane.setVerticalScrollBarPolicy(
                JScrollPane.VERTICAL_SCROLLBAR_ALWAYS);
      areaScrollPane.setPreferredSize(new Dimension(700, 600));
      
      //Put everything together.
        JPanel pane = new JPanel(new BorderLayout());
        pane.add(areaScrollPane, BorderLayout.CENTER);
        frame.add(pane, BorderLayout.CENTER);

	 /* allow user to close window  */
      frame.setDefaultCloseOperation(JFrame.HIDE_ON_CLOSE);

      frame.setVisible(true);
     	frame.pack();
     
      }
     public static void main(String[] args) { 
         HelpScreen hs = new HelpScreen();   //calls this class's constructor which creates the game
         
     }//ends main
}//ends class
