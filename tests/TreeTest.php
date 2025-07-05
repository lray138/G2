<?php

use lray138\G2\Tree;
use lray138\G2\Either;

it('creates a leaf tree correctly', function () {
    $tree = Tree::leaf('root');
    
    expect($tree)->toBeInstanceOf(Tree::class);
    expect($tree->getValue())->toBe('root');
    expect($tree->getChildren())->toBe([]);
    expect($tree->isLeaf())->toBe(true);
    expect($tree->isNode())->toBe(false);
});

it('creates a node tree correctly', function () {
    $child1 = Tree::leaf('child1');
    $child2 = Tree::leaf('child2');
    $tree = Tree::node('root', [$child1, $child2]);
    
    expect($tree)->toBeInstanceOf(Tree::class);
    expect($tree->getValue())->toBe('root');
    expect($tree->getChildren())->toHaveCount(2);
    expect($tree->isLeaf())->toBe(false);
    expect($tree->isNode())->toBe(true);
});

it('creates tree from array', function () {
    $data = [
        'value' => 'root',
        'children' => [
            [
                'value' => 'child1',
                'children' => [
                    ['value' => 'grandchild1']
                ]
            ],
            ['value' => 'child2']
        ]
    ];
    
    $tree = Tree::fromArray($data);
    
    expect($tree->getValue())->toBe('root');
    expect($tree->getChildren())->toHaveCount(2);
    expect($tree->getChildren()[0]->getValue())->toBe('child1');
    expect($tree->getChildren()[0]->getChildren())->toHaveCount(1);
    expect($tree->getChildren()[1]->getValue())->toBe('child2');
});

it('maps over tree values', function () {
    $tree = Tree::node('root', [
        Tree::leaf('child1'),
        Tree::leaf('child2')
    ]);
    
    $mapped = $tree->map(function($value) {
        return strtoupper($value);
    });
    
    expect($mapped->getValue())->toBe('ROOT');
    expect($mapped->getChildren()[0]->getValue())->toBe('CHILD1');
    expect($mapped->getChildren()[1]->getValue())->toBe('CHILD2');
});

it('filters tree nodes', function () {
    $tree = Tree::node('root', [
        Tree::leaf('apple'),
        Tree::leaf('banana'),
        Tree::leaf('cherry')
    ]);
    
    $filtered = $tree->filter(function($value) {
        return strpos($value, 'a') !== false;
    });
    
    expect($filtered->getValue())->toBe('root');
    expect($filtered->getChildren())->toHaveCount(2);
    expect($filtered->getChildren()[0]->getValue())->toBe('apple');
    expect($filtered->getChildren()[1]->getValue())->toBe('banana');
});

it('reduces tree values', function () {
    $tree = Tree::node(1, [
        Tree::leaf(2),
        Tree::leaf(3)
    ]);
    
    $sum = $tree->reduce(function($acc, $value) {
        return $acc + $value;
    }, 0);
    
    expect($sum)->toBe(6); // 1 + 2 + 3
});

it('calculates tree size', function () {
    $tree = Tree::node('root', [
        Tree::node('child1', [
            Tree::leaf('grandchild1'),
            Tree::leaf('grandchild2')
        ]),
        Tree::leaf('child2')
    ]);
    
    expect($tree->size())->toBe(5); // root + child1 + grandchild1 + grandchild2 + child2
});

it('calculates tree height', function () {
    $tree = Tree::node('root', [
        Tree::node('child1', [
            Tree::leaf('grandchild1')
        ]),
        Tree::leaf('child2')
    ]);
    
    expect($tree->height())->toBe(2);
});

it('finds nodes by predicate', function () {
    $tree = Tree::node('root', [
        Tree::leaf('apple'),
        Tree::leaf('banana'),
        Tree::leaf('cherry')
    ]);
    
    $result = $tree->find(function($value) {
        return $value === 'banana';
    });
    
    expect($result)->toBeInstanceOf(Either::class);
    expect($result instanceof \lray138\G2\Either\Right)->toBe(true);
    expect($result->extract()->getValue())->toBe('banana');
});

it('finds nodes by value', function () {
    $tree = Tree::node('root', [
        Tree::leaf('apple'),
        Tree::leaf('banana'),
        Tree::leaf('cherry')
    ]);
    
    $result = $tree->findValue('cherry');
    
    expect($result)->toBeInstanceOf(Either::class);
    expect($result instanceof \lray138\G2\Either\Right)->toBe(true);
    expect($result->extract()->getValue())->toBe('cherry');
});

it('checks if tree contains value', function () {
    $tree = Tree::node('root', [
        Tree::leaf('apple'),
        Tree::leaf('banana'),
        Tree::leaf('cherry')
    ]);
    
    expect($tree->contains('banana'))->toBe(true);
    expect($tree->contains('orange'))->toBe(false);
});

it('flattens tree to array', function () {
    $tree = Tree::node('root', [
        Tree::leaf('child1'),
        Tree::leaf('child2')
    ]);
    
    $flattened = $tree->flatten();
    
    expect($flattened)->toBe(['root', 'child1', 'child2']);
});

it('converts tree to array', function () {
    $tree = Tree::node('root', [
        Tree::leaf('child1'),
        Tree::leaf('child2')
    ]);
    
    $array = $tree->toArray();
    
    expect($array)->toBe([
        'value' => 'root',
        'children' => [
            ['value' => 'child1'],
            ['value' => 'child2']
        ]
    ]);
});

it('adds children to tree', function () {
    $tree = Tree::leaf('root');
    $child = Tree::leaf('child');
    
    $newTree = $tree->addChild($child);
    
    expect($newTree->getChildren())->toHaveCount(1);
    expect($newTree->getChildren()[0]->getValue())->toBe('child');
});

it('adds multiple children to tree', function () {
    $tree = Tree::leaf('root');
    $children = [
        Tree::leaf('child1'),
        Tree::leaf('child2')
    ];
    
    $newTree = $tree->addChildren($children);
    
    expect($newTree->getChildren())->toHaveCount(2);
    expect($newTree->getChildren()[0]->getValue())->toBe('child1');
    expect($newTree->getChildren()[1]->getValue())->toBe('child2');
});

it('concatenates trees', function () {
    $tree1 = Tree::leaf('root1');
    $tree2 = Tree::leaf('root2');
    
    $concatenated = $tree1->concat($tree2);
    
    expect($concatenated->getValue())->toBe('root1');
    expect($concatenated->getChildren())->toHaveCount(1);
    expect($concatenated->getChildren()[0]->getValue())->toBe('root2');
});

it('throws exception for null value in of()', function () {
    expect(function() {
        Tree::of(null);
    })->toThrow(Exception::class, "Tree::of() requires a valid value");
});

it('throws exception for invalid concat', function () {
    $tree = Tree::leaf('root');
    
    expect(function() use ($tree) {
        $tree->concat('not a tree');
    })->toThrow(Exception::class, 'Tree::concat() expects another Tree');
});

it('provides string representation', function () {
    $tree = Tree::node('root', [
        Tree::leaf('child1'),
        Tree::leaf('child2')
    ]);
    
    $string = $tree->toString();
    
    expect($string)->toContain('root');
    expect($string)->toContain('child1');
    expect($string)->toContain('child2');
}); 