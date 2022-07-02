FREECADPATH = '/usr/lib/freecad/lib'
import sys
sys.path.append(FREECADPATH)

import FreeCAD
import Part
import Mesh
shape = Part.Shape()
print('Convert ' + sys.argv[1] + ' to ' + sys.argv[2])
shape.read(sys.argv[1])
doc = App.newDocument('Doc')
pf = doc.addObject("Part::Feature","MyShape")
pf.Shape = shape
Mesh.export([pf], sys.argv[2])
