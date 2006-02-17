import reCompiler
import FSA
from tempfile import mkstemp

def fsa2dot(fsa):    
    inf = mkstemp('.dot')[1]
    f = open(inf, 'w')
    f.write(fsa.toDotString())
    f.close()
    
    outf = mkstemp('.dot')[1]
    bindir = "/Applications/Graphviz.app/Contents/MacOS"
    import os
    if not os.path.isfile(os.path.join(bindir, 'dot')):
        bindir = "/home/osteele/bin"
    import os
    os.system("%s %s -o %s" % (bindir+"/dot", inf, outf))
    f = open(outf)
    s = f.read()
    return s

def parseDot(s):
    import re
    nodes = {}
    edges = []
    defaults = {}
    # look greedy inside []
    for label, attrs in re.findall("([^[\n\t]+?)\s+\[(.*)\]", s):
        h = {}
        for k, v1, v2 in re.findall(r'([^=,\s]+)=(?:"((?:[^"\\]|\\.)*?)"|([^,""]*))', attrs):
            v = v1 or v2
            if k == 'pos' and v.startswith('e'):
                def str2pt(str):
                    x, y = str.split(',')
                    return {'x': float(x), 'y': float(y)}
                arp, v = re.match("e,(\d+,\d+)\s+(.*)", v).groups()
                h['endArrow'] = str2pt(arp)
            if k == 'label':
                v = re.sub(r'\\(["\\])', r'\1', v)
            h[k] = v
        if label == 'node':
            defaults = h
            continue
        match = re.match("(.*?)\s*->\s*(.*)", label)
        if match:
            # edge
            h['start'], h['stop'] = match.groups()
            h['pos'] = [{'x': float(ps[0]), 'y': float(ps[1])}
                        for ps in [xx.split(',') for xx in h['pos'].split(' ')]]
            edges += [h]
        else:
            # node
            h['x'], h['y'] = [float(n) for n in h['pos'].split(',')]
            if not h.has_key('shape') and defaults.has_key('shape'):
                h['shape'] = defaults['shape']
            nodes[label] = h
    return {'nodes': nodes, 'edges': edges}

def fsa2obj(fsa):
    def edge2obj(edge):
        s0, s1, cs = edge
        return {'start': s0, 'end': s1, 'edge': cs2obj(cs)}
    def cs2obj(cs):
        r = []
        for c0, c1 in cs.ranges:
            r += [chr(i) for i in range(ord(c0), ord(c1)+1)]
        return ''.join(r)
    return {'initialState': fsa.initialState,
            'finalStates': fsa.finalStates,
            'states': fsa.states,
            'transitions': map(edge2obj, fsa.transitions)}

#print reCompiler.compileRE('a[^a]*a')#.toDotString()
#print fsa2dot(reCompiler.compileRE(r'\\'))
#print parseDot(fsa2dot(reCompiler.compileRE(r'\\')))['edges']
#print reCompiler.compileRE('a|a')
#print fsa2obj(reCompiler.compileRE('[^a]'))['transitions'][0]
#from encoder import JSONEncoder
#print JSONEncoder().encode(fsa2obj(reCompiler.compileRE('[^a]'))['transitions'][0])
#print reCompiler.compileRE('\D').transitions[0][2].ranges
