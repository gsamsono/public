#Deliverable 2 - CS 228
#Grace Samsonow - fall 2015


import Leap, sys, thread, time, random
import matplotlib.pyplot as plt
from mpl_toolkits.mplot3d import Axes3D
import matplotlib
import numpy as np
import pickle

class Deliverable:
   def __init__(self):
      self.numberOfGestures = 1000
      self.gestureIndex = 0
      self.gestureData = np.zeros((5,4,6,self.numberOfGestures),dtype='f')
      #3D matrix w/ 5 rows for fingers, (0 is thumb)
      #4 columns for bones, (3 is distal phalange)
      #6 stacks for x,y,z coords of bone base & tip
      #(first 3 are base, last 3 are tip)
      # self.numberOfGesturesSaved = 0
      self.prevNumberOfHands = 0
      self.currNumberOfHands = 0
      self.controller = Leap.Controller()
      self.lines = []
      print "start..."
      matplotlib.interactive(True)
      self.fig =	plt.figure(	figsize=(8,6) )
      self.ax	= self.fig.add_subplot( 111,	projection='3d' )
      self.ax.set_xlim(-300,300)
      self.ax.set_ylim(-200,200)
      self.ax.set_zlim(0,400)
      self.ax.view_init(azim=90)
      plt.draw()

   def HandleBone(self,i,j):
      #for j in range(0,3): #for all bones in finger
         bone = self.finger.bone(j)
         boneBase = bone.prev_joint
         boneTip = bone.next_joint
         xBase = boneBase[0] #positionOfBoneBase[0]
         yBase = boneBase[1] #positionOfBoneBase[1]
         zBase = boneBase[2] #positionOfBoneBase[2]
         xTip = boneTip[0]   #positionOfBoneTip[0]
         yTip = boneTip[1]   #positionOfBoneTip[1]
         zTip = boneTip[2]   #positionOfBoneTip[2]
         if (self.currNumberOfHands == 1): #draw green lines
            self.lines.append(self.ax.plot([-xBase,-xTip],[zBase,zTip],[yBase,yTip],'g'))
         else:                         #draw red lines
            self.lines.append(self.ax.plot([-xBase,-xTip],[zBase,zTip],[yBase,yTip],'r'))
            #print self.currNumberOfHands
         if( (self.currNumberOfHands == 2) ): #and (self.RecordingEnding())
            self.gestureData[i,j,0,self.gestureIndex] = xBase
            self.gestureData[i,j,1,self.gestureIndex] = yBase
            self.gestureData[i,j,2,self.gestureIndex] = zBase
            self.gestureData[i,j,3,self.gestureIndex] = xTip
            self.gestureData[i,j,4,self.gestureIndex] = yTip
            self.gestureData[i,j,5,self.gestureIndex] = zTip
            #print "currNumberOfHands: ", self.currNumberOfHands
            #print self.gestureData[:,:,:,1]

   def HandleFinger(self,i):
      self.finger = self.hand.fingers[i]
      for j in range(0,4):
         self.HandleBone(i,j)
   
   def RecordingEnding(self):
      return (self.prevNumberOfHands==2) & (self.currNumberOfHands==1)
   
   def SaveGesture(self):
     #  self.numberOfGesturesSaved = self.numberOfGesturesSaved + 1
      fileName = 'userData/gesture.dat'#  + str(self.numberOfGesturesSaved) +'.dat'
      print fileName
      with open(fileName, "wb") as f:
         pickle.dump(self.gestureData, f)
      f.close()
      print 'file closed'
     #  fileName = 'userData/numOfGestures.dat'
#       f = open(fileName,'w')
#       f.write(str(self.numberOfGesturesSaved))
#       f.close()
      # print 'file closed'
   
   def HandleHands(self):
      #get the number of hands
      self.prevNumberOfHands = self.currNumberOfHands
      self.currNumberOfHands = len(self.frame.hands)
#       print self.prevNumberOfHands, self.currNumberOfHands
      self.hand = self.frame.hands[0]
      for i in range(0,5):
         self.HandleFinger(i)
      plt.draw()
      while ( len(self.lines)>0 ):
         ln = self.lines.pop()
         ln.pop(0).remove()
         del ln
         ln = []
      #if( self.RecordingEnding() ):
         # print 'recording is ending'
#          print self.gestureData[:,:,:]
         #self.SaveGesture()
      if ( self.currNumberOfHands == 2 ):
         print 'gesture ' + str(self.gestureIndex) + ' stored.'
         self.gestureIndex = self.gestureIndex + 1
         if ( self.gestureIndex == self.numberOfGestures ):
            #print self.gestureData[:,:,:,99]
            self.SaveGesture()
            sys.exit(0)
   
   def RunOnce(self):
       self.frame = self.controller.frame()
       if not (self.frame.hands.is_empty and self.frame.gestures().is_empty):
           self.HandleHands()
           # for hand in self.frame.hands:
#                #handType = "Left hand" if hand.is_left else "Right hand"
#                #print "%s, id %d, position: %s" % (handType, hand.id, hand.palm_position)
#                self.HandleHands()


   def RunForever(self):
      while ( True ):
         self.RunOnce()


deliverable = Deliverable()
deliverable.RunForever()

