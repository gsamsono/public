import pickle
import numpy as np
from sklearn import neighbors, datasets

#left hands
# train1 = pickle.load( open('userData/Samsonow_train1.p','rb') )
# test1 = pickle.load( open('userData/Samsonow_test1.p','rb') )
# train3 = pickle.load( open('userData/Samsonow_train3.p','rb') )
# test3 = pickle.load( open('userData/Samsonow_test3.p','rb') )
# train5 = pickle.load( open('userData/Nealon_train5.p','rb') )
# test5 = pickle.load( open('userData/Nealon_test5.p','rb') )

#right hands
train1 = pickle.load( open('userData/Margolis_train1.p','rb') )
test1 = pickle.load( open('userData/Margolis_test1.p','rb') )
train2 = pickle.load( open('userData/Mee_train2.p','rb') )
test2 = pickle.load( open('userData/Mee_test2.p','rb') )
train3 = pickle.load( open('userData/Flore_train3.p','rb') )
test3 = pickle.load( open('userData/Flore_test3.p','rb') )
#4 wasnt working very well w/ Sheehan and Bilodeau
train4 = pickle.load( open('userData/Samsonow_train4.p','rb') )
test4 = pickle.load( open('userData/Samsonow_test4.p','rb') )
train5 = pickle.load( open('userData/Siegel_train5.p','rb') )
test5 = pickle.load( open('userData/Siegel_test5.p','rb') )
train6 = pickle.load( open('userData/Kiley_train6.p','rb') )
test6 = pickle.load( open('userData/Kiley_test6.p','rb') )
train7 = pickle.load( open('userData/Nguyen_train7.p','rb') )
test7 = pickle.load( open('userData/Nguyen_test7.p','rb') )
train8 = pickle.load( open('userData/Nguyen_train8.p','rb') )
test8 = pickle.load( open('userData/Nguyen_test8.p','rb') )
train9 = pickle.load( open('userData/Lewis_train9.p','rb') )
test9 = pickle.load( open('userData/Lewis_test9.p','rb') )
train0 = pickle.load( open('userData/Margolis_train0.p','rb') )
test0 = pickle.load( open('userData/Margolis_test0.p','rb') )

def ReduceData(X):
   X = np.delete(X,1,1) #remove prox. & inter. phalanges
   X = np.delete(X,1,1)
   X = np.delete(X,0,2) #remove bases of bones
   X = np.delete(X,0,2)
   X = np.delete(X,0,2)
   return X

def CenterData(X):
   #get mean of X coords
   allXCoords = X[:,:,0,:]
   XmeanValue = allXCoords.mean()
   X[:,:,0,:] = allXCoords - XmeanValue
   #print X[:,:,0,:].mean()
   #get mean of Y coords
   allYCoords = X[:,:,1,:]
   YmeanValue = allYCoords.mean()
   X[:,:,1,:] = allYCoords - YmeanValue
   #print X[:,:,1,:].mean()
   #get mean of Z coords
   allZCoords = X[:,:,2,:]
   ZmeanValue = allZCoords.mean()
   X[:,:,2,:] = allZCoords - ZmeanValue
   #print X[:,:,2,:].mean()
   return X

def ReshapeData(set1,set2,set3,set4,set5,set6,set7,set8,set9,set0):
   X = np.zeros((10000,5*2*3),dtype='f')
   y = np.array([0] *10000)
   for i in range(0,1000):
      n = 0
      for j in range(0,5): #5 fingers
         for k in range(0,2): #2 bones (removed prox. & inter. phalanges)
            for m in range(0,3): #bone tips only, no bases
               X[i,n] = set1[j,k,m,i] #0-999 X[i,n] X[0:999,:]
               y[i] = '1'
               X[i+1000,n] = set2[j,k,m,i] #1000-1999 X[i+1000,n] X[1000:1999,:]
               y[i+1000] = '2'
               X[i+2000,n] = set3[j,k,m,i] #X[2000:2999,:]
               y[i+2000] = '3'
               X[i+3000,n] = set4[j,k,m,i]
               y[i+3000] = '4'
               X[i+4000,n] = set5[j,k,m,i]
               y[i+4000] = '5'
               X[i+5000,n] = set6[j,k,m,i]
               y[i+5000] = '6'
               X[i+6000,n] = set7[j,k,m,i]
               y[i+6000] = '7'
               X[i+7000,n] = set8[j,k,m,i]
               y[i+7000] = '8'
               X[i+8000,n] = set9[j,k,m,i]
               y[i+8000] = '9'
               X[i+9000,n] = set0[j,k,m,i]
               y[i+9000] = '0'
               n = n + 1
   return X, y

train1 = ReduceData(train1)
train1 = CenterData(train1)
test1 = ReduceData(test1)
test1 = CenterData(test1)
train2 = ReduceData(train2)
train2 = CenterData(train2)
test2 = ReduceData(test2)
test2 = CenterData(test2)
train3 = ReduceData(train3)
train3 = CenterData(train3)
test3 = ReduceData(test3)
test3 = CenterData(test3)
train4 = ReduceData(train4)
train4 = CenterData(train4)
test4 = ReduceData(test4)
test4 = CenterData(test4)
train5 = ReduceData(train5)
train5 = CenterData(train5)
test5 = ReduceData(test5)
test5 = CenterData(test5)
train6 = ReduceData(train6)
train6 = CenterData(train6)
test6 = ReduceData(test6)
test6 = CenterData(test6)
train7 = ReduceData(train7)
train7 = CenterData(train7)
test7 = ReduceData(test7)
test7 = CenterData(test7)
train8 = ReduceData(train8)
train8 = CenterData(train8)
test8 = ReduceData(test8)
test8 = CenterData(test8)
train9 = ReduceData(train9)
train9 = CenterData(train9)
test9 = ReduceData(test9)
test9 = CenterData(test9)
train0 = ReduceData(train0)
train0 = CenterData(train0)
test0 = ReduceData(test0)
test0 = CenterData(test0)

trainX, trainy = ReshapeData(train1,train2,train3,train4,train5,train6,train7,train8,train9,train0)
testX, testy = ReshapeData(test1,test2,test3,test4,test5,test6,test7,test8,test9,test0)

clf = neighbors.KNeighborsClassifier(15)
clf.fit(trainX,trainy)
numCorrect = 0.0
numItems = 10000 #originally 2000
for p in range(0,numItems):
   itemClass = int(testy[p])
   prediction = int(clf.predict(testX[p,:]))
   #print itemClass, prediction
   if (itemClass == prediction):
      numCorrect = numCorrect + 1.0
print "# correctly predicted: ", numCorrect
print "% correctly predicted: ", (numCorrect/float(numItems))*100.0


pickle.dump(clf, open('userData/classifier.p','wb'))

