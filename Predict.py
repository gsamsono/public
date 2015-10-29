import numpy as np
import matplotlib.pyplot as plt
from sklearn import neighbors, datasets

#edge color = prediction
#inner color = actual class

iris = datasets.load_iris()
trainX = iris.data[::2,1:3] #::2 even numbered rows, cols 1-2 (2nd & 3rd)
trainy = iris.target[::2]   #trainy is a vector
testX = iris.data[1::2,1:3] #1::2 odd numbered rows, cols 1-2 (2nd & 3rd)
testy = iris.target[1::2]

clf = neighbors.KNeighborsClassifier(15) #K=15
clf.fit(trainX,trainy) #trains the classifier

#changes colors
colors = np.zeros((3,3),dtype='f')
colors[0,:] = [1.0, 0.5, 0.5]  #light red, 1st row
colors[1,:] = [0.5, 1.0, 0.5]  #light green, 2nd row
colors[2,:] = [0.5, 0.5, 1.0]  #light blue, 3rd row
plt.figure()

[numItems, numFeatures] = iris.data.shape  #shape is # of rows and cols
for i in range(0,numItems/2):
   itemClass = int(trainy[i])
   currColor = colors[itemClass,:]
   plt.scatter(trainX[i,0],trainX[i,1],facecolor=currColor,edgecolor=[0,0,0],s=50,lw=2)
counter = 0.0
for i in range(0,numItems/2):
   itemClass = int(testy[i])
   currColor = colors[itemClass,:]
   prediction = int(clf.predict(testX[i,:]))
   edgeColor = colors[prediction,:] #edge color = prediction. inner color = actual class
   if (itemClass == prediction):
      counter = counter + 1.0
   plt.scatter(testX[i,0],testX[i,1],facecolor=currColor,edgecolor=edgeColor,s=50,lw=2)
print "# correctly predicted: ", counter
print "% correctly predicted: ", (counter/float(numItems/2.0))*100.0
plt.show()

#raw_input('') #stops code here
