/** Grace Samsonow 
final project - CS205 - spring 2015
this class creates the GUI for the game 'theres a moose in the house'
uses classes: HelpScreen, Card, Deck, Game, Hand, House, Player, DiscardPile
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


public class TestMooseGUI extends JFrame
{
   // Variable declarations and assignments
   @SuppressWarnings("unchecked")
   
   private JFrame jFrame1 = new JFrame();                   //main frame
   private JPanel player2panel = new JPanel();              //panel for player 2
   private JLabel p2label = new JLabel("Mooseolini's House"); //label for player 2
   private JLabel p2MITRcountLabel = new JLabel();          //# of moose in the room cards in player 2's house
   private JPanel player3panel = new JPanel();              //panel for player 3
   private JLabel p3label = new JLabel();                   //label for player 3
   private JLabel p3MITRcountLabel = new JLabel();          //# of moose in the room cards in player 3's house
   private JPanel player4panel = new JPanel();              //panel for player 4
   private JLabel p4label = new JLabel();                   //label for player 4
   private JLabel p4MITRcountLabel = new JLabel();          //# of moose in the room cards in player 4's house
   private JButton quitButton = new JButton("Quit");        //button to quit the game
   private JButton helpButton = new JButton("Help");        //button to display a help window with instructions
   private JButton clearButton = new JButton("Clear Buttons");        //button to clear all selected buttons
   private JButton statsButton = new JButton("Game Statistics");        //button to show the game stats
   private JPanel player1panel = new JPanel();              //panel for player 1
   private JLabel p1label = new JLabel("Your House");       //label for player 1
   private JLabel p1MITRcountLabel = new JLabel();          //# of moose in the room cards in player 1's house
   private JPanel handPanel = new JPanel();                 //panel for player 1's hand
   private JLabel handLabel = new JLabel("Your Hand");      //label for player 1's hand
   private JLabel gameStatusLabel = new JLabel("Game in progress...");  //update during player turns etc
   //buttons for the players houses
   private JToggleButton p1HouseButton0, p1HouseButton1, p1HouseButton2, p1HouseButton3; //player 1
   private JToggleButton p2HouseButton0, p2HouseButton1, p2HouseButton2, p2HouseButton3; //player 2
   private JToggleButton p3HouseButton0, p3HouseButton1, p3HouseButton2, p3HouseButton3; //player 3
   private JToggleButton p4HouseButton0, p4HouseButton1, p4HouseButton2, p4HouseButton3; //player 4
   //labels for the players counts of closed door cards
   private JLabel p1CDcountLabel = new JLabel();
   private JLabel p2CDcountLabel = new JLabel();
   private JLabel p3CDcountLabel = new JLabel();
   private JLabel p4CDcountLabel = new JLabel();
   //buttons for the cards in the users hand
   private JToggleButton handTButton0, handTButton1, handTButton2, handTButton3, handTButton4;
   //images on the buttons in the users hand
   private BufferedImage handButtonIcon0, handButtonIcon1, handButtonIcon2, handButtonIcon3, handButtonIcon4;
   //buffered image objects to show the players cards in their houses
   private BufferedImage p1HouseButtonIcon0, p1HouseButtonIcon1, p1HouseButtonIcon2, p1HouseButtonIcon3; //player 1
   private BufferedImage p2HouseButtonIcon0, p2HouseButtonIcon1, p2HouseButtonIcon2, p2HouseButtonIcon3; //player 2
   private BufferedImage p3HouseButtonIcon0, p3HouseButtonIcon1, p3HouseButtonIcon2, p3HouseButtonIcon3; //player 3
   private BufferedImage p4HouseButtonIcon0, p4HouseButtonIcon1, p4HouseButtonIcon2, p4HouseButtonIcon3; //player 4
   private BufferedImage discardButtonIcon;                    //image on the button for the discard pile
   private JToggleButton discardButton = new JToggleButton();  //the discard pile
   private JLabel discardLabel = new JLabel("Discard Pile:");  //discard pile label
   public int numPlayers;       //holds the number of players in the game
   public Card selectedCard;    //the card that the user selects in their hand and wants to play
   public Game game;            //a new Game object
   //counters to keep count of how many 'moose in the room' cards each player has
   public int p1MITRcount, p2MITRcount, p3MITRcount, p4MITRcount;
   public int p1CDcount, p2CDcount, p3CDcount, p4CDcount;
   public Player player1, player2, player3, player4; //the 4 players
   public Deck deck; //the deck
   public Card handCard0, handCard1, handCard2, handCard3, handCard4;      //card objects in the users hand
   public ImageIcon handIcon0, handIcon1, handIcon2, handIcon3, handIcon4; //the icons to put on the users hand
   //public DiscardPile dpile = new DiscardPile();
   public Player[] players;    //the array of player objects
   public int playerNum = -1;  //when nothing is selected
   public int houseNum = -1;   //when nothing is selected
   public boolean bool;        //the status of the popup window (closed or open)
   public int handCard;        //an arg for the changeDiscardPileIcon function
   
   
   /**  default constructor. sets up the GUI and the elements of the game  */
   public TestMooseGUI(int numPlayers) throws FileNotFoundException
   {
      super("There's a Moose in the House!"); //set the window title
      jFrame1.setBackground(new java.awt.Color(128, 178, 201));
      jFrame1.setForeground(new java.awt.Color(128, 178, 201));
      player1panel.setOpaque(true); //trying to change the background color
      player2panel.setOpaque(true);
      player3panel.setOpaque(true);
      player4panel.setOpaque(true);
      handPanel.setOpaque(true);
      
      this.numPlayers = numPlayers;      //the number of players in the game
      players = new Player[numPlayers];  //the array of players
      statsButton.setVisible(false);     //doesnt need to be visible until the game ends
      
      //set the buttons showing the cards to transparent imgs at the game start
      try
      {
         //player 1's house buttons to show their cards
         BufferedImage p1HouseButtonIcon0 = ImageIO.read(new File("card_imgs/transparent2.gif"));
         p1HouseButton0 = new JToggleButton(new ImageIcon(p1HouseButtonIcon0));
         BufferedImage p1HouseButtonIcon1 = ImageIO.read(new File("card_imgs/ERspace2.gif"));
         p1HouseButton1 = new JToggleButton(new ImageIcon(p1HouseButtonIcon1));
         BufferedImage p1HouseButtonIcon2 = ImageIO.read(new File("card_imgs/ERspace2.gif"));
         p1HouseButton2 = new JToggleButton(new ImageIcon(p1HouseButtonIcon2));
         BufferedImage p1HouseButtonIcon3 = ImageIO.read(new File("card_imgs/ERspace2.gif"));
         p1HouseButton3 = new JToggleButton(new ImageIcon(p1HouseButtonIcon3));
         //player 2's house buttons to show their cards
         BufferedImage p2HouseButtonIcon0 = ImageIO.read(new File("card_imgs/transparent2.gif"));
         p2HouseButton0 = new JToggleButton(new ImageIcon(p2HouseButtonIcon0));
         BufferedImage p2HouseButtonIcon1 = ImageIO.read(new File("card_imgs/ERspace2.gif"));
         p2HouseButton1 = new JToggleButton(new ImageIcon(p2HouseButtonIcon1));
         BufferedImage p2HouseButtonIcon2 = ImageIO.read(new File("card_imgs/ERspace2.gif"));
         p2HouseButton2 = new JToggleButton(new ImageIcon(p2HouseButtonIcon2));
         BufferedImage p2HouseButtonIcon3 = ImageIO.read(new File("card_imgs/ERspace2.gif"));
         p2HouseButton3 = new JToggleButton(new ImageIcon(p2HouseButtonIcon3));
         //player 1's hand cards as toggle buttons
         BufferedImage handButtonIcon0 = ImageIO.read(new File("card_imgs/transparent2.gif"));
         handTButton0 = new JToggleButton(new ImageIcon(handButtonIcon0));
         BufferedImage handButtonIcon1 = ImageIO.read(new File("card_imgs/transparent2.gif"));
         handTButton1 = new JToggleButton(new ImageIcon(handButtonIcon1));
         BufferedImage handButtonIcon2 = ImageIO.read(new File("card_imgs/transparent2.gif"));
         handTButton2 = new JToggleButton(new ImageIcon(handButtonIcon2));
         BufferedImage handButtonIcon3 = ImageIO.read(new File("card_imgs/transparent2.gif"));
         handTButton3 = new JToggleButton(new ImageIcon(handButtonIcon3));
         BufferedImage handButtonIcon4 = ImageIO.read(new File("card_imgs/transparent2.gif"));
         handTButton4 = new JToggleButton(new ImageIcon(handButtonIcon4));
         //the discard pile button
         BufferedImage discardButtonIcon = ImageIO.read(new File("card_imgs/transparent2.gif"));
         discardButton = new JToggleButton(new ImageIcon(discardButtonIcon));
      }
      //to catch an exception if the file isnt found
      catch (IOException ioe)
      {
         throw new FileNotFoundException("An image was not found. Program closing.");
      }
      
      //display main frame and set layout
javax.swing.GroupLayout jFrame1Layout = new javax.swing.GroupLayout(jFrame1.getContentPane());
        jFrame1.getContentPane().setLayout(jFrame1Layout);
        jFrame1Layout.setHorizontalGroup(
            jFrame1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGap(0, 400, Short.MAX_VALUE)
        );
        jFrame1Layout.setVerticalGroup(
            jFrame1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGap(0, 300, Short.MAX_VALUE)
        );

        //sets close operation and title of GUI window
        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);
        setTitle("There's a Moose in the House!");

        //creates player 2's part of the game board area
        p2MITRcountLabel.setText("0 Moose in the Room cards");
        p2CDcountLabel.setText("0 Closed Door cards");

javax.swing.GroupLayout player2panelLayout = new javax.swing.GroupLayout(player2panel);
        player2panel.setLayout(player2panelLayout);
        player2panelLayout.setHorizontalGroup(
            player2panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(player2panelLayout.createSequentialGroup()
                .addGroup(player2panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(player2panelLayout.createSequentialGroup()
                        .addComponent(p2label)
                        .addGap(38, 38, 38))
                    .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, player2panelLayout.createSequentialGroup()
                        .addComponent(p2HouseButton0)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)))
                .addGroup(player2panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(player2panelLayout.createSequentialGroup()
                        .addGap(2, 2, 2)
                        .addComponent(p2HouseButton1)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addComponent(p2HouseButton2)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(p2HouseButton3)
                        .addGap(6, 6, 6))
                    .addGroup(player2panelLayout.createSequentialGroup()
                        .addGroup(player2panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(p2CDcountLabel)
                            .addComponent(p2MITRcountLabel))
                        .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))))
        );
        player2panelLayout.setVerticalGroup(
            player2panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(player2panelLayout.createSequentialGroup()
                .addGroup(player2panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(p2label)
                    .addComponent(p2MITRcountLabel))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(p2CDcountLabel)
                .addGap(11, 11, 11)
                .addGroup(player2panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(p2HouseButton0)
                    .addComponent(p2HouseButton1)
                    .addComponent(p2HouseButton2)
                    .addComponent(p2HouseButton3))
                .addGap(0, 29, Short.MAX_VALUE))
        );

//create player 3's and player 4's parts of the game board area if needed
      if (numPlayers >= 3)
      {
         createPlayer3();
      } //ends if (numPlayers >= 3)
      
      if (numPlayers == 4)
      {
         //createPlayer3();
         createPlayer4();
         
      } //ends if (numPlayers == 4)


      //creates player 1's part of the game board area
        p1MITRcountLabel.setText("0 Moose in the Room cards");
        p1CDcountLabel.setText("0 Closed Door cards");

javax.swing.GroupLayout player1panelLayout = new javax.swing.GroupLayout(player1panel);
        player1panel.setLayout(player1panelLayout);
        player1panelLayout.setHorizontalGroup(
            player1panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(player1panelLayout.createSequentialGroup()
                .addGroup(player1panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(player1panelLayout.createSequentialGroup()
                        .addComponent(p1label)
                        .addGap(29, 29, 29)
                        .addGroup(player1panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(p1MITRcountLabel)
                            .addComponent(p1CDcountLabel)))
                    .addGroup(player1panelLayout.createSequentialGroup()
                        .addGap(4, 4, 4)
                        .addComponent(p1HouseButton0)
                        .addGap(14, 14, 14)
                        .addComponent(p1HouseButton1)
                        .addGap(12, 12, 12)
                        .addComponent(p1HouseButton2)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(p1HouseButton3)))
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        player1panelLayout.setVerticalGroup(
            player1panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(player1panelLayout.createSequentialGroup()
                .addGroup(player1panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(p1label)
                    .addComponent(p1MITRcountLabel))
                .addGap(4, 4, 4)
                .addComponent(p1CDcountLabel)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addGroup(player1panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(p1HouseButton0)
                    .addComponent(p1HouseButton1)
                    .addComponent(p1HouseButton2)
                    .addComponent(p1HouseButton3))
                .addContainerGap(22, Short.MAX_VALUE))
        );

      //displays the users hand - panel and buttons
javax.swing.GroupLayout handPanelLayout = new javax.swing.GroupLayout(handPanel);
        handPanel.setLayout(handPanelLayout);
        handPanelLayout.setHorizontalGroup(
            handPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(handPanelLayout.createSequentialGroup()
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGroup(handPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(handPanelLayout.createSequentialGroup()
                        .addComponent(handTButton0)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(handTButton2)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(handTButton4))
                    .addGroup(handPanelLayout.createSequentialGroup()
                        .addGroup(handPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(handLabel)
                            .addComponent(handTButton1))
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(handTButton3)))
                .addContainerGap(46, Short.MAX_VALUE))
        );
        handPanelLayout.setVerticalGroup(
            handPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(handPanelLayout.createSequentialGroup()
                .addContainerGap()
                .addComponent(handLabel)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(handPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(handTButton0)
                    .addComponent(handTButton2)
                    .addComponent(handTButton4))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(handPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(handTButton1)
                    .addComponent(handTButton3))
                .addContainerGap(58, Short.MAX_VALUE))
        );

   //display panels
javax.swing.GroupLayout layout = new javax.swing.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(layout.createSequentialGroup()
                .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                    .addComponent(player3panel, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(player4panel, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(player2panel, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, layout.createSequentialGroup()
                        .addGap(0, 0, Short.MAX_VALUE)
                        .addComponent(discardButton))
                    .addGroup(layout.createSequentialGroup()
                        .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(discardLabel)
                            .addComponent(helpButton)
                            .addComponent(quitButton)
                            .addComponent(clearButton)
                            .addComponent(statsButton))
                        .addGap(0, 0, Short.MAX_VALUE)))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(handPanel, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addGroup(layout.createSequentialGroup()
                        .addGap(6, 6, 6)
                        .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(gameStatusLabel)
                            .addComponent(player1panel, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))))
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        layout.setVerticalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(layout.createSequentialGroup()
                .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(layout.createSequentialGroup()
                        .addComponent(player2panel, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(player3panel, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                    .addGroup(layout.createSequentialGroup()
                        .addContainerGap()
                        .addComponent(discardLabel)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(discardButton)
                        .addGap(77, 77, 77)
                        .addComponent(quitButton)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(clearButton)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(statsButton)))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(player4panel, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addGap(0, 11, Short.MAX_VALUE))
            .addGroup(layout.createSequentialGroup()
                .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING)
                    .addComponent(helpButton)
                    .addComponent(handPanel, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(player1panel, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(gameStatusLabel)
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        pack(); //pack objects into the frame
      
      
      /********************* add action listeners for buttons and set it all visible *********************/
      
      //action listener for the handTButton0 button, in the users hand
      handTButton0.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                handTButton0ActionPerformed(evt);
            }
        });
      //action listener for the handTButton1 button, in the users hand
      handTButton1.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                handTButton1ActionPerformed(evt);
            }
        });
      //action listener for the handTButton2 button, in the users hand
      handTButton2.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                handTButton2ActionPerformed(evt);
            }
        });
      //action listener for the handTButton3 button, in the users hand
      handTButton3.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                handTButton3ActionPerformed(evt);
            }
        });
        //action listener for the handTButton4 button, in the users hand
      handTButton4.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                handTButton4ActionPerformed(evt);
            }
        });
///////// PLAYER 1's HOUSE BUTTONS
        //action listener for p1HouseButton0
      p1HouseButton0.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                p1HouseButton0ActionPerformed(evt);
            }
        });
        //action listener for p1HouseButton1
      p1HouseButton1.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                p1HouseButton1ActionPerformed(evt);
            }
        });
        //action listener for p1HouseButton2
      p1HouseButton2.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                p1HouseButton2ActionPerformed(evt);
            }
        });
        //action listener for p1HouseButton3
      p1HouseButton3.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                p1HouseButton3ActionPerformed(evt);
            }
        });
////////// PLAYER 2's HOUSE BUTTONS        
        //action listener for p2HouseButton0
      p2HouseButton0.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                p2HouseButton0ActionPerformed(evt);
            }
        });
        //action listener for p2HouseButton1
      p2HouseButton1.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                p2HouseButton1ActionPerformed(evt);
            }
        });
        //action listener for p2HouseButton2
      p2HouseButton2.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                p2HouseButton2ActionPerformed(evt);
            }
        });
        //action listener for p2HouseButton3
      p2HouseButton3.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                p2HouseButton3ActionPerformed(evt);
            }
        });
/////// OTHER BUTTONS ACTIONS LISTENERS        
      //action listener for the clearButton button
      clearButton.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                clearButtonActionPerformed(evt);
            }
        });
      //action listener for the stats button
      statsButton.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                statsButtonActionPerformed(evt);
            }
        });
        //action listener for the discard button
      discardButton.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                discardButtonActionPerformed(evt);
            }
        });
      //action listener for the quit button
      quitButton.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                quitButtonActionPerformed(evt);
            }
        });
      //action listener for the help button
      helpButton.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                helpButtonActionPerformed(evt);
            }
        });
        
      setVisible(true);
  } //ends TestMooseGUI() constructor ******************************************************/


 //creates player 3's part of the game board area
   public void createPlayer3() throws FileNotFoundException {
      try {
        //player 3's house buttons to show their cards
         BufferedImage p3HouseButtonIcon0 = ImageIO.read(new File("card_imgs/transparent2.gif"));
         p3HouseButton0 = new JToggleButton(new ImageIcon(p3HouseButtonIcon0));
         BufferedImage p3HouseButtonIcon1 = ImageIO.read(new File("card_imgs/ERspace2.gif"));
         p3HouseButton1 = new JToggleButton(new ImageIcon(p3HouseButtonIcon1));
         BufferedImage p3HouseButtonIcon2 = ImageIO.read(new File("card_imgs/ERspace2.gif"));
         p3HouseButton2 = new JToggleButton(new ImageIcon(p3HouseButtonIcon2));
         BufferedImage p3HouseButtonIcon3 = ImageIO.read(new File("card_imgs/ERspace2.gif"));
         p3HouseButton3 = new JToggleButton(new ImageIcon(p3HouseButtonIcon3));
      } //ends try
      //to catch an exception if the file isnt found
      catch (IOException ioe)
      {
         throw new FileNotFoundException("An image was not found. Program closing.");//didnt find the transparent img");//
      } //ends catch
        //set text on labels
        p3label.setText("Lady Antlerbellum's House");
        p3MITRcountLabel.setText("0 Moose in the Room cards");
        p3CDcountLabel.setText("0 Closed Door cards");
        
////////// PLAYER 3's HOUSE BUTTONS        
        //action listener for p3HouseButton0
      p3HouseButton0.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                p3HouseButton0ActionPerformed(evt);
            }
        });
        //action listener for p3HouseButton1
      p3HouseButton1.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                p3HouseButton1ActionPerformed(evt);
            }
        });
        //action listener for p3HouseButton2
      p3HouseButton2.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                p3HouseButton2ActionPerformed(evt);
            }
        });
        //action listener for p3HouseButton3
      p3HouseButton3.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                p3HouseButton3ActionPerformed(evt);
            }
        });
//player 3's layout
javax.swing.GroupLayout player3panelLayout = new javax.swing.GroupLayout(player3panel);
        player3panel.setLayout(player3panelLayout);
        player3panelLayout.setHorizontalGroup(
            player3panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(player3panelLayout.createSequentialGroup()
                .addGroup(player3panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(player3panelLayout.createSequentialGroup()
                        .addComponent(p3label)
                        .addGap(30, 30, 30)
                        .addGroup(player3panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(p3CDcountLabel)
                            .addComponent(p3MITRcountLabel)))
                    .addGroup(player3panelLayout.createSequentialGroup()
                        .addGap(2, 2, 2)
                        .addComponent(p3HouseButton0)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(p3HouseButton1)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(p3HouseButton2)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(p3HouseButton3)))
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        player3panelLayout.setVerticalGroup(
            player3panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(player3panelLayout.createSequentialGroup()
                .addGroup(player3panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(p3label)
                    .addComponent(p3MITRcountLabel))
                .addGap(3, 3, 3)
                .addComponent(p3CDcountLabel)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addGroup(player3panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(p3HouseButton0)
                    .addComponent(p3HouseButton1)
                    .addComponent(p3HouseButton2)
                    .addComponent(p3HouseButton3))
                .addGap(0, 32, Short.MAX_VALUE))
        );
   } //ends createPlayer3()


 //creates player 4's part of the game board area
   public void createPlayer4() throws FileNotFoundException {
      try {
         //player 4's house buttons to show their cards
         BufferedImage p4HouseButtonIcon0 = ImageIO.read(new File("card_imgs/transparent2.gif"));
         p4HouseButton0 = new JToggleButton(new ImageIcon(p4HouseButtonIcon0));
         BufferedImage p4HouseButtonIcon1 = ImageIO.read(new File("card_imgs/ERspace2.gif"));
         p4HouseButton1 = new JToggleButton(new ImageIcon(p4HouseButtonIcon1));
         BufferedImage p4HouseButtonIcon2 = ImageIO.read(new File("card_imgs/ERspace2.gif"));
         p4HouseButton2 = new JToggleButton(new ImageIcon(p4HouseButtonIcon2));
         BufferedImage p4HouseButtonIcon3 = ImageIO.read(new File("card_imgs/ERspace2.gif"));
         p4HouseButton3 = new JToggleButton(new ImageIcon(p4HouseButtonIcon3));
      } //ends try
      //to catch an exception if the file isnt found
      catch (IOException ioe)
      {
         throw new FileNotFoundException("An image was not found. Program closing.");//didnt find the transparent img");//
      } //ends catch
        //set text on labels
        p4label.setText("Moosetapha's House");
        p4MITRcountLabel.setText("0 Moose in the Room cards");
        p4CDcountLabel.setText("0 Closed Door cards");
////////// PLAYER 4's HOUSE BUTTONS        
        //action listener for p4HouseButton0
      p4HouseButton0.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                p4HouseButton0ActionPerformed(evt);
            }
        });
        //action listener for p4HouseButton1
      p4HouseButton1.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                p4HouseButton1ActionPerformed(evt);
            }
        });
        //action listener for p4HouseButton2
      p4HouseButton2.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                p4HouseButton2ActionPerformed(evt);
            }
        });
        //action listener for p4HouseButton3
      p4HouseButton3.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent evt) {
                p4HouseButton3ActionPerformed(evt);
            }
        });
//player 4's layout
javax.swing.GroupLayout player4panelLayout = new javax.swing.GroupLayout(player4panel);
        player4panel.setLayout(player4panelLayout);
        player4panelLayout.setHorizontalGroup(
            player4panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(player4panelLayout.createSequentialGroup()
                .addGroup(player4panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(player4panelLayout.createSequentialGroup()
                        .addComponent(p4label)
                        .addGap(32, 32, 32)
                        .addGroup(player4panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(p4CDcountLabel)
                            .addComponent(p4MITRcountLabel)))
                    .addGroup(player4panelLayout.createSequentialGroup()
                        .addComponent(p4HouseButton0)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(p4HouseButton1)
                        .addGap(4, 4, 4)
                        .addComponent(p4HouseButton2)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(p4HouseButton3)))
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        player4panelLayout.setVerticalGroup(
            player4panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(player4panelLayout.createSequentialGroup()
                .addGroup(player4panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(p4label)
                    .addComponent(p4MITRcountLabel))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(p4CDcountLabel)
                .addGap(18, 18, 18)
                .addGroup(player4panelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(p4HouseButton0)
                    .addComponent(p4HouseButton1)
                    .addComponent(p4HouseButton2)
                    .addComponent(p4HouseButton3))
                .addGap(0, 29, Short.MAX_VALUE))
        );
   } //ends createPlayer4()

   //will displays a string saying whose turn it is etc
   public void setGameStatusLabel(String str)
   {
      gameStatusLabel.setText(str);
   }
   
   //gets the number of 'moose in the room' cards for the player
   public int getMITRcount(int i) //i is the player #
   {
      return players[i].house.getFullRooms();
   }
   //gets the number of 'closed door' cards for the player
   public int getCDcount(int i) //i is the player #
   {
      return players[i].house.getClosedRooms();
   }

   //sets the count of 'moose in the room' cards for the players labels
   public void setMITRcount()
   {
      p1MITRcount = getMITRcount(0);
      p1MITRcountLabel.setText(p1MITRcount+" Moose in the Room cards");
      p2MITRcount = getMITRcount(1);
      p2MITRcountLabel.setText(p2MITRcount+" Moose in the Room cards");
      if(numPlayers >= 3){
        p3MITRcount = getMITRcount(2);
        p3MITRcountLabel.setText(p3MITRcount+" Moose in the Room cards");
      } if(numPlayers == 4){
        p4MITRcount = getMITRcount(3);
        p4MITRcountLabel.setText(p4MITRcount+" Moose in the Room cards");
      }
   }
   
   //sets the count of 'closed door' cards for the players labels
   public void setCDcount()
   {
      p1CDcount = getCDcount(0);
      p1CDcountLabel.setText(p1CDcount +" closed doors");
      p2CDcount = getCDcount(1);
      p2CDcountLabel.setText(p2CDcount +" closed doors");
      if(numPlayers >= 3){
         p3CDcount = getCDcount(2);
         p3CDcountLabel.setText(p3CDcount +" closed doors");
      } if(numPlayers == 4){
         p4CDcount = getCDcount(3);
         p4CDcountLabel.setText(p4CDcount +" closed doors");
      }
   }


//FUNCTIONS FOR WHEN BUTTONS ARE PRESSED ---------------------------------------------------
   //this function is called when the handTButton0 button is pressed, in the users hand
   public void handTButton0ActionPerformed(ActionEvent evt) 
   {
       handTButton0.setSelected(true);
       handTButton3.setSelected(false);
       handTButton1.setSelected(false);
       handTButton2.setSelected(false);
       handTButton4.setSelected(false);
   }
   //this function is called when the handTButton1 button is pressed, in the users hand
   public void handTButton1ActionPerformed(ActionEvent evt) 
   {
       handTButton1.setSelected(true);
       handTButton0.setSelected(false);
       handTButton3.setSelected(false);
       handTButton2.setSelected(false);
       handTButton4.setSelected(false);
   }
   //this function is called when the handTButton2 button is pressed, in the users hand
   public void handTButton2ActionPerformed(ActionEvent evt) 
   {
       handTButton2.setSelected(true);
       handTButton0.setSelected(false);
       handTButton1.setSelected(false);
       handTButton3.setSelected(false);
       handTButton4.setSelected(false);
   }
   //this function is called when the handTButton3 button is pressed, in the users hand
   public void handTButton3ActionPerformed(ActionEvent evt) 
   {
       handTButton3.setSelected(true);
       handTButton0.setSelected(false);
       handTButton1.setSelected(false);
       handTButton2.setSelected(false);
       handTButton4.setSelected(false);
   }
   //this function is called when the handTButton4 button is pressed, in the users hand
   public void handTButton4ActionPerformed(ActionEvent evt) 
   {
       handTButton4.setSelected(true);
       handTButton0.setSelected(false);
       handTButton1.setSelected(false);
       handTButton2.setSelected(false);
       handTButton3.setSelected(false);
   }
   
   //player 1's house buttons, called when pressed
   public void p1HouseButton0ActionPerformed(ActionEvent evt)
   {//player 1 is player 0 in the array of player objects
      setVictim(0,0);//(player #, house slot #) house slots are numbered 0-3
   }
   public void p1HouseButton1ActionPerformed(ActionEvent evt)
   {
      setVictim(0,1);
   }
   public void p1HouseButton2ActionPerformed(ActionEvent evt)
   {
      setVictim(0,2);
   }
   public void p1HouseButton3ActionPerformed(ActionEvent evt)
   {
      setVictim(0,3);
   }
   //player 2's house buttons, called when pressed
   public void p2HouseButton0ActionPerformed(ActionEvent evt)
   {
      setVictim(1,0);
   }
   public void p2HouseButton1ActionPerformed(ActionEvent evt)
   {
      setVictim(1,1);
   }
   public void p2HouseButton2ActionPerformed(ActionEvent evt)
   {
      setVictim(1,2);
   }
   public void p2HouseButton3ActionPerformed(ActionEvent evt)
   {
      setVictim(1,3);
   }
   //player 3's house buttons, called when pressed
   public void p3HouseButton0ActionPerformed(ActionEvent evt)
   {
      setVictim(2,0);
   }
   public void p3HouseButton1ActionPerformed(ActionEvent evt)
   {
      setVictim(2,1);
   }
   public void p3HouseButton2ActionPerformed(ActionEvent evt)
   {
      setVictim(2,2);
   }
   public void p3HouseButton3ActionPerformed(ActionEvent evt)
   {
      setVictim(2,3);
   }
   //player 4's house buttons, called when pressed
   public void p4HouseButton0ActionPerformed(ActionEvent evt)
   {
      setVictim(3,0); //args are (playerNum, houseNum)
   }
   public void p4HouseButton1ActionPerformed(ActionEvent evt)
   {
      setVictim(3,1);
   }
   public void p4HouseButton2ActionPerformed(ActionEvent evt)
   {
      setVictim(3,2);
   }
   public void p4HouseButton3ActionPerformed(ActionEvent evt)
   {
      setVictim(3,3);
   }
   
   //listener for when the popup window is closed
   public void popupClosed(WindowEvent evt) 
   {                             
      setPopupStatus(true);//true = window is closed
   }
   
   //gets the status of the popup window
   public boolean getPopupStatus()
   {
      return bool;
   }
   
   //sets the status of the popup window
   public void setPopupStatus(boolean bool)
   {
      this.bool = bool;
   }
   
   //gets the house number that is being played on
   public int getVictimHouse()
   {
      //houseNum = 99; //when nothing is selected
      return houseNum;
   }
   //gets the player number that is being played on
   public int getVictimPlayer()
   {
      //playerNum = 99; //when nothing is selected
      return playerNum;
   }
   //sets the house and victim that the user is playing a card on
   public void setVictim(int playerNum, int houseNum)
   {
      this.playerNum = playerNum;
      this.houseNum = houseNum;
   }   
   
   //set the players as an array of player objects
   public void setPlayers(Player[] p)
   {
      players = p;
   }
   
   //returns an int for the card in the users hand thats selected
   public int getSelectedHandButton()
   {
      if(handTButton0.isSelected())
      {
         return 0;
      }
      else if(handTButton1.isSelected())
      {
         return 1;
      }
      else if(handTButton2.isSelected())
      {
         return 2;
      }
      else if(handTButton3.isSelected())
      {
         return 3;
      }
      else if(handTButton4.isSelected())
      {
         return 4;
      }
      else //if no card is selected so the user cant make a move
      {
         return 99;
      }
   } //ends getSelectedHandButton() function
   
   //returns the status of the discard button
   public boolean getDiscardButtonStatus()
   {
      return discardButton.isSelected();
   }
   
   //sets all the buttons to unselected, and clears the victim player
   public void clearButtons()
   {
       setVictim(-1, -1);//set the victim player and house to invalid
       discardButton.setSelected(false);  //discard button
       handTButton0.setSelected(false);
       handTButton1.setSelected(false);
       handTButton2.setSelected(false);
       handTButton3.setSelected(false);
       handTButton4.setSelected(false);   //player 1's hand buttons
       p1HouseButton0.setSelected(false);
       p1HouseButton1.setSelected(false);
       p1HouseButton2.setSelected(false);
       p1HouseButton3.setSelected(false); //player 1's buttons
       p2HouseButton0.setSelected(false);
       p2HouseButton1.setSelected(false);
       p2HouseButton2.setSelected(false);
       p2HouseButton3.setSelected(false); //player 2's buttons
       if(numPlayers >= 3){
         p3HouseButton0.setSelected(false);
         p3HouseButton1.setSelected(false);
         p3HouseButton2.setSelected(false);
         p3HouseButton3.setSelected(false); //player 3's buttons
       } if(numPlayers == 4){
         p4HouseButton0.setSelected(false);
         p4HouseButton1.setSelected(false);
         p4HouseButton2.setSelected(false);
         p4HouseButton3.setSelected(false); //player 4's buttons
       }
   }
   
   //called when the clear button is pressed
   public void clearButtonActionPerformed(ActionEvent evt) 
   {
      clearButtons();
   }
   //called when the stats button is pressed
   public void statsButtonActionPerformed(ActionEvent evt)// throws IOException
   {
      try{//call the stats class when the buttons is pressed
         statScreen stats = new statScreen();
      }
      catch(IOException e){
         System.out.println("The file does not exist " + e);
      } 
   }
   //sets the stats button visible after the game ends
   //so the user can click it and see the game stats
   public void setStatsButtonVisible(boolean bool){
      statsButton.setVisible(bool); 
   }
   //this function is called when the discard button is pressed
   public void discardButtonActionPerformed(ActionEvent evt) 
   {
      discardButton.setSelected(true);
   }
   //this function is called when the help button is pressed
   public void helpButtonActionPerformed(ActionEvent evt) 
   {
       HelpScreen hs = new HelpScreen();
   }
   //this function is called when the quit button is pressed
   private void quitButtonActionPerformed(ActionEvent evt) 
   {
       System.exit(0);
   }
   
   //adds a MITH image to a card in a house
   public void addMITHHouseImage(int victimPlayerIndex, String filename) throws FileNotFoundException
   {
      try {
         if (victimPlayerIndex == 0)//player 1
         {
             p1HouseButtonIcon0 = ImageIO.read(new File(filename));
             ImageIcon icon = new ImageIcon(p1HouseButtonIcon0);
             p1HouseButton0.setIcon(icon);
         }
         if (victimPlayerIndex == 1)//player 2
         {
             p2HouseButtonIcon0 = ImageIO.read(new File(filename));
             ImageIcon icon = new ImageIcon(p2HouseButtonIcon0);
             p2HouseButton0.setIcon(icon);
         }
         if ( (victimPlayerIndex == 2) && (numPlayers >= 3) )//player 3
         {
             p3HouseButtonIcon0 = ImageIO.read(new File(filename));
             ImageIcon icon = new ImageIcon(p3HouseButtonIcon0);
             p3HouseButton0.setIcon(icon);
         }
         if ( (victimPlayerIndex == 3) && (numPlayers == 4) )//player 4
         {
             p4HouseButtonIcon0 = ImageIO.read(new File(filename));
             ImageIcon icon = new ImageIcon(p4HouseButtonIcon0);
             p4HouseButton0.setIcon(icon);
         }
      } //ends try
      catch (IOException ioe) {
         throw new FileNotFoundException("An image was not found. Program closing.");//didnt find the transparent img");//
      }
   } //ends addMITHHouseImage function
   
   //adds an ER image to a card in a house
   public void addERHouseImage(int numPlayers, int victimPlayerIndex, int playCardIndex, String filename) throws FileNotFoundException
   {  //victimPlayerIndex = the victim player #
      //playCardIndex = the location in that players house
      this.numPlayers = numPlayers;
     try {
         //victim player is player 1
        if ( (victimPlayerIndex == 0) && (playCardIndex == 1) )
        {
            p1HouseButtonIcon1 = ImageIO.read(new File(filename));
            ImageIcon icon = new ImageIcon(p1HouseButtonIcon1);
            p1HouseButton1.setIcon(icon);
        }
        if ( (victimPlayerIndex == 0) && (playCardIndex == 2) )
        {
            p1HouseButtonIcon2 = ImageIO.read(new File(filename));
            ImageIcon icon = new ImageIcon(p1HouseButtonIcon2);
            p1HouseButton2.setIcon(icon);
        }
        if ( (victimPlayerIndex == 0) && (playCardIndex == 3) )
        {
            p1HouseButtonIcon3 = ImageIO.read(new File(filename));
            ImageIcon icon = new ImageIcon(p1HouseButtonIcon3);
            p1HouseButton3.setIcon(icon);
        } //ends all of the buttons for player[0] / p1
         //victim player is player 2
        if ( (victimPlayerIndex == 1) && (playCardIndex == 1) )
        {
            p2HouseButtonIcon1 = ImageIO.read(new File(filename));
            ImageIcon icon = new ImageIcon(p2HouseButtonIcon1);
            p2HouseButton1.setIcon(icon);
        }
        if ( (victimPlayerIndex == 1) && (playCardIndex == 2) )
        {
            p2HouseButtonIcon2 = ImageIO.read(new File(filename));
            ImageIcon icon = new ImageIcon(p2HouseButtonIcon2);
            p2HouseButton2.setIcon(icon);
        }
        if ( (victimPlayerIndex == 1) && (playCardIndex == 3) )
        {
            p2HouseButtonIcon3 = ImageIO.read(new File(filename));
            ImageIcon icon = new ImageIcon(p2HouseButtonIcon3);
            p2HouseButton3.setIcon(icon);
        } //ends all of the buttons for player[1] / p2
         //victim player is player 3
        if (numPlayers >= 3){
         if ( (victimPlayerIndex == 2) && (playCardIndex == 1) )
         {
             p3HouseButtonIcon1 = ImageIO.read(new File(filename));
             ImageIcon icon = new ImageIcon(p3HouseButtonIcon1);
             p3HouseButton1.setIcon(icon);
         }
         if ( (victimPlayerIndex == 2) && (playCardIndex == 2) )
         {
             p3HouseButtonIcon2 = ImageIO.read(new File(filename));
             ImageIcon icon = new ImageIcon(p3HouseButtonIcon2);
             p3HouseButton2.setIcon(icon);
         }
         if ( (victimPlayerIndex == 2) && (playCardIndex == 3) )
         {
             p3HouseButtonIcon3 = ImageIO.read(new File(filename));
             ImageIcon icon = new ImageIcon(p3HouseButtonIcon3);
             p3HouseButton3.setIcon(icon);
         } //ends all of the buttons for player[2] / p3
        }
         //victim player is player 4
        if (numPlayers == 4) {
         if ( (victimPlayerIndex == 3) && (playCardIndex == 1) )
         {
             p4HouseButtonIcon1 = ImageIO.read(new File(filename));
             ImageIcon icon = new ImageIcon(p4HouseButtonIcon1);
             p4HouseButton1.setIcon(icon);
         }
         if ( (victimPlayerIndex == 3) && (playCardIndex == 2) )
         {
             p4HouseButtonIcon2 = ImageIO.read(new File(filename));
             ImageIcon icon = new ImageIcon(p4HouseButtonIcon2);
             p4HouseButton2.setIcon(icon);
         }
         if ( (victimPlayerIndex == 3) && (playCardIndex == 3) )
         {
             p4HouseButtonIcon3 = ImageIO.read(new File(filename));
             ImageIcon icon = new ImageIcon(p4HouseButtonIcon3);
             p4HouseButton3.setIcon(icon);
         } //ends all of the buttons for player[3] / p4
        }
      } //ends try
      catch (IOException ioe) {
         throw new FileNotFoundException("An image was not found. Program closing.");
      }
   } //ends addERHouseImage function
   
   //removes the image of the card in the users hand
   public void removeHandImage(int handCard) throws FileNotFoundException
   {
      try { //sets the icons on the users hand cards to transparent imgs
         if (handCard == 0) 
         {
            handButtonIcon0 = ImageIO.read(new File("card_imgs/transparent2.gif"));
            ImageIcon icon = new ImageIcon(handButtonIcon0);
            handTButton0.setIcon(icon);
         }
         if (handCard == 1) 
         {
            handButtonIcon1 = ImageIO.read(new File("card_imgs/transparent2.gif"));
            ImageIcon icon = new ImageIcon(handButtonIcon1);
            handTButton1.setIcon(icon);
         }
         if (handCard == 2) 
         {
            handButtonIcon2 = ImageIO.read(new File("card_imgs/transparent2.gif"));
            ImageIcon icon = new ImageIcon(handButtonIcon2);
            handTButton2.setIcon(icon);
         }
         if (handCard == 3) 
         {
            handButtonIcon3 = ImageIO.read(new File("card_imgs/transparent2.gif"));
            ImageIcon icon = new ImageIcon(handButtonIcon3);
            handTButton3.setIcon(icon);
         }
         if (handCard == 4) 
         {
            handButtonIcon4 = ImageIO.read(new File("card_imgs/transparent2.gif"));
            ImageIcon icon = new ImageIcon(handButtonIcon4);
            handTButton4.setIcon(icon);
         }
      } //ends try
      catch (IOException ioe) {
         throw new FileNotFoundException("An image was not found. Program closing.");
      }
   } //ends removeHandImage function
   
   //changes the icon on the discard pile after a card is added to it
   //filename = the filename of the card thats on the discard pile
   public void changeDiscardPileIcon(String filename) throws FileNotFoundException
   {
      try {
         discardButtonIcon = ImageIO.read(new File(filename));
      }
      catch (IOException ioe) {
         throw new FileNotFoundException("An image was not found. Program closing.");
      }
      ImageIcon icon = new ImageIcon(discardButtonIcon);
      //sets the discard button to the image of the card that the user discarded
      discardButton.setIcon(icon);
   } //ends changeDiscardPileIcon function
   
   //displays a window when the users tries to execute an invalid move
   public void badMovePopup()
   {
      JFrame popup = new JFrame("Error!"); //create the frame
      JTextArea info = new JTextArea("You have moves available but that move was not valid! Please try again!");
      info.setLineWrap(true); //allow word wrapping
      info.setWrapStyleWord(true);
      info.setEditable(false); //do not allow the user to edit the instructions
      //create a pane to allow for scrolling
      JScrollPane areaScrollPane = new JScrollPane(info);
      areaScrollPane.setVerticalScrollBarPolicy(JScrollPane.VERTICAL_SCROLLBAR_ALWAYS);
      areaScrollPane.setPreferredSize(new Dimension(300, 100));
      //Put everything together.
      JPanel pane = new JPanel(new BorderLayout());
      pane.add(areaScrollPane, BorderLayout.CENTER);
      popup.add(pane, BorderLayout.CENTER);
      popup.setDefaultCloseOperation(JFrame.HIDE_ON_CLOSE); /* allow user to close window  */
      popup.setVisible(true);
     	popup.pack();
      //listener for the popup window
      addWindowListener(new java.awt.event.WindowAdapter() {
            public void windowDeactivated(java.awt.event.WindowEvent evt) {
                popupClosed(evt);
            }
        }); //listener and functions for this are on lines 850-866
   }//ends badMovePopup function
   
   //sets the image of the card in the users (players[0]) hand
   public void setHandCard(Player player)
   {
      handCard0 = player.hand.getCard(0);
      handCard1 = player.hand.getCard(1);
      handCard2 = player.hand.getCard(2);
      handCard3 = player.hand.getCard(3);
      handCard4 = player.hand.getCard(4);
      
      String imgStr0 = handCard0.getImage();
      handIcon0 = new ImageIcon(imgStr0);
      String imgStr1 = handCard1.getImage();
      handIcon1 = new ImageIcon(imgStr1);
      String imgStr2 = handCard2.getImage();
      handIcon2 = new ImageIcon(imgStr2);
      String imgStr3 = handCard3.getImage();
      handIcon3 = new ImageIcon(imgStr3);
      String imgStr4 = handCard4.getImage();
      handIcon4 = new ImageIcon(imgStr4);
      
      handTButton0.setIcon(handIcon0);
      handTButton1.setIcon(handIcon1);
      handTButton2.setIcon(handIcon2);
      handTButton3.setIcon(handIcon3);
      handTButton4.setIcon(handIcon4);
   } //ends setHandCard() func
   
} //ends TestMooseGUI class