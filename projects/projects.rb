# http://www.imagemagick.org/script/command-line-options.php
# http://www.cit.gu.edu.au/~anthony/graphics/imagick6/thumbnails/
# http://www.cit.gu.edu.au/~anthony/graphics/imagick6/annotating/

require 'rexml/document'
require 'erb'

include REXML

module REXML
  class Element
    def first_element xpath
      get_elements(xpath).first
    end
  end
end

class Array
  def sort_by! reverse=false, &block
    if reverse
      sort! {|a, b| block.call(b) <=> block.call(a)}
    else
      sort! {|a, b| block.call(a) <=> block.call(b)}
    end
  end
  #def sort_by! reverse=false, &block
  #  block = proc {-yield} if reverse
  #  sort! &block
  #end
end
#a=[1,4,2,3];a.sort_by! {|a,b|a<=>b};p a
#a=[1,4,2,3];a.sort_by!(true) {|a,b|a<=>b};p a

class XMLProxy
  attr_reader :base # for debugging
  
  def initialize(base, ns=nil)
    @base = base
    @ns = ns
  end
  
  def method_missing(sym, options={})
    name = sym.to_s
    ns = options[:ns] || @ns
    q = ns ? "#{ns}:" : ""
    qname = "#{q}#{name}"
    es = @base.get_elements(qname)
    case
    when es.length == 1
      return convert(es.first, ns, options[:type])
    when es.empty? && @base.attributes[name]
      return @base.attributes[name]
    else
      return es.map { |e| convert(e, ns, options[:type]) }
    end
  end
  
  private
  def convert(e, ns, type)
    text = e.text
    text = Date.parse(text) if type == Date
    return XMLProxy.new(e, ns) if e.has_elements? or text.nil?
    return text
  end
end

class Project
  @@fields = 'name created description tags homepage image documentation blog 
              languages company role sources skip screencast'.split.map(&:intern)
  attr_accessor *@@fields
  
  def self.fields
    @@fields
  end
  
  def initialize
    @tags = []
  end
  
  def created=(date)
    date = date.sub(/-\d\d(-\d\d)/, '') if date.gsub(/^.*(\d\d\d\d).*$/, '\1').to_i < 2005
    @created = date
    #@created = Date.parse(date)
  end
  
  def self.normcase(t)
    abbrs = %w{SQL PHP HTML XSLT HMM RDF FSA RIA AJAX}
    return "<abbr>#{t.upcase}</abbr>" if abbrs.include? t.upcase
    acronyms = %w{FOAF}
    return "<acronym>#{t.upcase}</acronym>" if acronyms.include? t.upcase
    norms = %w{Apple Commodore-64Flash DocBook Flash Google-Maps Macintosh MacOS OpenLaszlo Rails WordNet WordPress}
    norms += %w{C Java Python Ruby C++ Dylan Lisp JavaScript Pascal Assembly}
    h = Hash[*norms.map { |w| [w.downcase,w]}.flatten ]
    h[t] || t
  end
  
  def homepage=(url)
    @homepage = url
    @tags << 'online'
  end
  
  def sources=(url)
    @sources = url
    @tags << 'sources'
  end
  
  def tags=(new_tags)
    @tags += new_tags
  end

  def public_tags
    (tags + public_technologies - %w[major minor]).
      map(&:downcase).
      sort.
      uniq
  end
  
  def public_technologies
    languages.sort.map { |w| Project.normcase(w) }
  end
  
  def thumbnail
    image = @image
    image ||= 'images/python-logo.png (-transparent white)' if languages.include?('python')
    image ||= 'images/java-logo.jpg' if languages.include?('java')
    return unless image
    image =~ /(.*?)(?:\s*\((.*)\))?$/
    src, options = $1, $2
    src.sub!(/^\//, '../')
    target = 'images/' + src.sub(/^.*?([^\/]*?)(?:-small|-large)?\.([^.\/]+)$/, '\1-thumb.png')
    return src if File.exists?("#{target}.skip")
    unless File.exists?(target)
      width = `identify #{src}`[/(\d+)x(\d+)/, 1].to_i
      `convert -resize '150>' #{options} #{src} #{target}`
      if !options && width <= 150 && File.size(src)-File.size(target) < 2048
        File.delete target
        File.open "#{target}.skip", 'w' do |f| f << 'skip' end
        return src
      end
    end
    return target
  end

  def date_str
    case created
    when /\d+-\d+-\d+/
      Date.parse(created).strftime('%B %Y')
    when /(\d{4})-\d{4}/
      $1
    when /\d+-\d+/
      Date.parse("#{created}-01").strftime('%B %Y')
    else
      created.to_s
    end
  end
end

def yaml_to_project(y)
  project = Project.new
  for key in Project.fields do
    key = key.to_s
    if y[key]
      value = y[key].value
      type = Object
      type = Array if %w[tags languages].include?(key)
      value = value.split if type == Array
      case
      when %w[homepage weblog].include?(key)
        value = "http://osteele.com#{value}" if value =~ /^\//
      when key == 'description'
        value.gsub!(/\\'/, "'") if key == :description
      end
      project.send("#{key}=", value)
    end
  end
  raise "No creation date for #{project.name}" unless project.created
  project
end

class ProjectView
  def self.engine
    require 'haml'
    fname = 'project.html.haml'
    @engine ||= Haml::Engine.new(open(fname).read(), :filename => fname)
  end
  
  attr_reader :project, :i
  def initialize(project, i)
    @project = project
    @i = i
  end

  def render(s)
    bindings = {
      :project => project,
      :project_id => "project-#{i}",
      :color => format("%02x", (255*(0.95-0.3*s)).to_i)*3,
      :fgcolor => format("%02x", (255*(0.2+0.3*s)).to_i)*3,
      :astart => nil,
      :aend => nil,
      :url => project.homepage || project.blog || project.screencast || project.documentation || project.sources
    }
    if project.homepage
      bindings[:astart] = %Q{<a href="#{project.homepage || project.documentation}">}
      bindings[:aend] = %Q{</a>}
    end
    require 'haml'
    fname = 'project.html.haml'
    engine = self.class.engine
    engine.render(nil, bindings)
  end
end

def projects
  projects = YAML.parse_file('projects.yaml').
    children.
    map { |y| yaml_to_project(y) }.
    select { |p| !p.skip }
  projects -= projects.select { |p|
    (p.tags & %w[rails-plugin ruby-gem library]).any? &&
      !p.image
  }
  return projects
end

def make_index(target='projects.php')
  open('projects.php.tmp', 'w') do |f|
    f << '<table><tr>'
    projects.each_with_index do |project, i|
      f << '</tr><tr>' if i > 0 and i % 3 == 0
      f << '<td valign="top">'
      f << ProjectView.new(project, i).render(i.to_f / projects.length)
      f << "</td>\n"
    end
    f << '</tr></table>'
  end
  FileUtils::mv 'projects.php.tmp', target
  nil
end

def make_xml(target='projects.xml')
  require 'yaml'
  require 'builder'
  xm = Builder::XmlMarkup.new(:indent => 2)
  s = xm.projects {
    projects.each_with_index do |project, i|
      searchtext = [
        project.name,
        project.created,
        project.company,
        project.role,
        project.description,
        project.tags,
        project.languages].
        compact.flatten.join(' ').
        downcase.gsub(/<\/?.*?>/, '').gsub(/[<>"'=\/]/, ' ').
        gsub(/\s+/m, ' ')
      xm.project(:text => searchtext,
                 :name => project.name,
                 :index => i,
                 :tags => (project.tags+project.languages).uniq.join(' '))
    end
  }
  open(target, 'w') do |f| f.write(s) end
end
