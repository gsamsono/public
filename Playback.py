import matplotlib.pyplot as plt
from mpl_toolkits.mplot3d import Axes3D
import matplotlib
import numpy as np
import pickle
import time

class Reader:
   def __init__(self):
      self.lines = []
      #print "start..."
      matplotlib.interactive(True)
      self.fig =	plt.figure(	figsize=(8,6) )
      self.ax	= self.fig.add_subplot( 111,	projection='3d' )
      self.ax.set_xlim(-300,300)
      self.ax.set_ylim(-200,200)
      self.ax.set_zlim(0,400)
      self.ax.view_init(azim=90)
      plt.draw()
      
      self.numberOfGesturesSaved = 0
      fileName = 'userData/numOfGestures.dat'
      f = open(fileName,'r')
      line = f.readline().strip()
      self.numberOfGesturesSaved = int(line)
      f.close()
      
   def PrintGesture(self,i):
      i = i + 1
      fileName = 'userData/gesture' + str(i) +'.dat'
      #print fileName
      with open(fileName, "rb") as f:
         self.gestureData = pickle.load(f)
      #print self.gestureData
      for i in range(0,5):
         for j in range(0,4):
            xBase = self.gestureData[i,j,0]
            yBase = self.gestureData[i,j,1]
            zBase = self.gestureData[i,j,2]
            xTip = self.gestureData[i,j,3]
            yTip = self.gestureData[i,j,4]
            zTip = self.gestureData[i,j,5]
            #print xBase, yBase, zBase, xTip, yTip, zTip
            self.lines.append(self.ax.plot([-xBase,-xTip],[zBase,zTip],[yBase,yTip],'b'))
      plt.draw()
      while ( len(self.lines)>0 ):
         ln = self.lines.pop()
         ln.pop(0).remove()
         del ln
         ln = []
      time.sleep(0.5)
   
   def PrintData(self):
      for i in range(0,self.numberOfGesturesSaved):
         #print i
         self.PrintGesture(i)
         
   def RunForever(self):
      while ( True ):
         self.PrintData()
      
reader = Reader()
reader.RunForever()
