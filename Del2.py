#Deliverable 2 used again in deliv 6 - CS 228
#Grace Samsonow - fall 2015


import Leap, sys, thread, time, random
import matplotlib.pyplot as plt
from mpl_toolkits.mplot3d import Axes3D
import matplotlib
import pickle
import numpy as np

clf = pickle.load( open('userData/classifier.p','rb') )
testData = np.zeros((1,30),dtype='f')
controller = Leap.Controller()
print "start..."

matplotlib.interactive(True)
fig = plt.figure( figsize=(8,6) )
ax = fig.add_subplot( 111, projection='3d' )
ax.set_xlim(-300,300)
ax.set_ylim(-200,200)
ax.set_zlim(0,400)
ax.view_init(azim=90)
plt.draw()

def CenterData(testData):
   #get mean of X coords
   allXCoords = testData[0,::3] #[:,:,0,:]
   XmeanValue = allXCoords.mean()
   testData[0,::3] = allXCoords - XmeanValue
   #print testData[:,:,0,:].mean()
   #get mean of Y coords
   allYCoords = testData[0,1::3] #[:,:,1,:]
   YmeanValue = allYCoords.mean()
   testData[0,1::3] = allYCoords - YmeanValue
   #print testData[:,:,1,:].mean()
   #get mean of Z coords
   allZCoords = testData[0,2::3] #[:,:,2,:]
   ZmeanValue = allZCoords.mean()
   testData[0,2::3] = allZCoords - ZmeanValue
   #print testData[:,:,2,:].mean()
   return testData
   
#mirrors the hand to be a right hand, if it's a left hand
#doesnt always work...
def scale_transformation(hand):
   basis = hand.basis
   x_basis = (basis.x_basis * -1)
   y_basis = (basis.y_basis * -1)
   z_basis = (basis.z_basis * -1)
   return hand

while ( True ):
   frame = controller.frame()
   lines = []
   if not (frame.hands.is_empty and frame.gestures().is_empty):
      k = 0
      for hand in frame.hands:
         #handType = "Left hand" if hand.is_left else "Right hand"
         #print "  %s, id %d, position: %s" % (handType, hand.id, hand.palm_position)
         for i in range(0,5): #for all fingers in hand
            finger = hand.fingers[i]
            for j in range(0,4): #for all bones in finger
                  bone = finger.bone(j)
                  boneBase = bone.prev_joint
                  boneTip = bone.next_joint
                  xBase = boneBase[0]
                  yBase = boneBase[1]
                  zBase = boneBase[2]
                  xTip = boneTip[0]
                  yTip = boneTip[1]
                  zTip = boneTip[2]
                  lines.append(ax.plot([-xBase,-xTip],[zBase,zTip],[yBase,yTip],'r'))
                  if ( (j==0) | (j==3) ):
                     testData[0,k] = xTip
                     testData[0,k+1] = yTip
                     testData[0,k+2] = zTip
                     k = k + 3
         if (hand.is_left):
            hand = scale_transformation(hand)
         testData = CenterData(testData)
         predictedClass = clf.predict(testData)
         print predictedClass
         plt.draw()
         
         while ( len(lines)>0 ):
            ln = lines.pop()
            ln.pop(0).remove()
            del ln
            ln = []


